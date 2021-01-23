<?php namespace ZN\Crontab;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Base;
use ZN\Config;
use ZN\Singleton;
use ZN\Filesystem;
use ZN\DataTypes\Arrays;
use ZN\Crontab\Exception\InvalidTimeFormatException;

class Job implements JobInterface, CrontabIntervalInterface
{
    use CrontabIntervalTrait;

    /**
     * Command Type
     * 
     * @var string
     */
    protected $type;

    /**
     * Is Debug
     * 
     * @var bool
     */
    protected $debug = false;

    /**
     * Crontab Directory
     * 
     * @var string
     */
    protected $crontabDir = '';

    /**
     * Jobs
     * 
     * @var array
     */
    protected $jobs = [];

    /**
     * Basic Structure
     * 
     * @var string
     */
    protected $directoryIndexCommand;

    /**
     * Define
     * 
     * @var string
     */
    protected $projectCommand = NULL;

    /**
     * Crontab Commands
     * 
     * @var string
     */
    protected $crontabCommands;

    /**
     * Crontab File Name
     * 
     * @var string
     */
    protected $fileName = 'Crontab' . DS . 'Jobs';

    /**
     * @var string
     */
    protected $user = NULL;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $command;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $limitFile = 'Limit.json';

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        $this->getConfig = Config::default('ZN\Prompt\PromptDefaultConfiguration')
                                 ::get('Services', 'processor');
        $this->directoryIndexCommand = $this->getDirectoryIndexCommand();
        $this->processor = Singleton::class('ZN\Prompt\Processor');

        if( PROJECT_TYPE === 'EIP' )
        {
            $this->crontabCommands = EXTERNAL_DIR . $this->fileName;
            $this->user            = DEFINED_CURRENT_PROJECT;
            
            $this->getProjectCommand($this->projectCommand = $this->directoryIndexCommand);
        }
        else
        {
            $this->crontabCommands = FILES_DIR . $this->fileName;
        }

        $this->createCrontabDirectoryIfNotExists();

        $this->path       = $this->getConfig['path'];
        $this->crontabDir = Filesystem\Info::originpath(STORAGE_DIR.'Crontab'.DS);
    }


    /**
     * Send parameters
     * 
     * @param mixed ...$arguments
     */
    public function parameters(...$parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get crontab commands.
     */
    public function getCrontabCommands()
    {
        return $this->crontabCommands;
    }

    /**
     * Crontab Queue
     * 
     * @param callable $callable
     * @param int      $decrement = 1
     */
    public function queue(Callable $callable, Int $decrement = 1)
    {
        new Queue($callable, $decrement, $this);
    }

    /**
     * Crontab limit
     * 
     * @param int $getLimit = 1
     */
    public function limit(Int $getLimit = 1)
    {
        new Limit($getLimit, $this);
    }

    /**
     * Selects project
     * 
     * @param string $name
     * 
     * @return Job
     */
    public function project(String $name)
    {
        $this->user = $name;

        $this->crontabDir = str_replace(REQUESTED_CURRENT_PROJECT, $this->user, $this->crontabDir);

        $this->getProjectCommand($this->projectCommand);

        return $this;
    }

    /**
     * Select Processor Driver
     * 
     * @param string $driver
     * 
     * @return Job
     */
    public function driver(String $driver) : Job
    {
        $this->processor->driver($driver);
        
        return $this;
    }

    /**
     * Gets crontab list array
     * 
     * @return array
     */
    public function listArray() : Array
    {
        if( ! is_file($this->crontabCommands) )
        {
            return [];
        }

        return Arrays\RemoveElement::element(explode(EOL, file_get_contents($this->crontabCommands)), '');
    }

    /**
     * Gets crontab list
     * 
     * @return string
     */
    public function list() : String
    {
        $list = '';

        if( is_file($this->crontabCommands) )
        {
            $jobs  = $this->listArray();
            $list  = '<pre>';
            $list .= '[ID] CRON JOB<br><br>';

            foreach( $jobs as $key => $job )
            {
                $list .= '[' . $key . ']: '. $job . '<br>';
            }

            $list .= '</pre>';
        }

        return $list;
    }

    /**
     * Last job
     * 
     * @return string
     */
    public function lastJob()
    {
        return $this->processor->exec('crontab -l');
    }

    /**
     * Remove cron job
     * 
     * @param string $key = NULL
     */
    public function remove($key = NULL)
    {
        $this->executeRemoveCommand();

        if( $key === NULL )
        {
            unlink($this->crontabCommands);
        }
        else
        {
            if( is_numeric($key) )
            {
                $this->removeJobFromExecFile($key);
            }
            else
            {
                $this->removeJobFromExecFileWithTerm($key);
            }
            
            $this->executeCommand();
        }
    }

    /**
     * Debug status
     * 
     * @param bool $status = true
     * 
     * @return Job
     */
    public function debug(Bool $status = true) : Job
    {
        $this->debug = $status;
        return $this;
    }

    /**
     * Cron Controller
     * 
     * @param string $file
     */
    public function controller(String $file)
    {
        new ControllerCommand($file, $command);
        
        $code = Base::prefix(Base::suffix($command, ';\''), ' -r \'' . $this->directoryIndexCommand);

        return $this->run($code);
    }

    /**
     * Cron wget
     * 
     * @param string $url
     */
    public function wget(String $url)
    {
        $this->path('wget');

        return $this->run($url);
    }

    /**
     * Cron Command
     * 
     * @param string $file
     * @param string $type = 'Project' - options[Project|External]
     */
    public function command(String $file, $type = 'Project')
    {
        $path     = $this->convertFileName($file);
        $pathEx   = explode('-', $path);
        $command  = $pathEx[0];
        $method   = $pathEx[1] ?? Config::get('Routing', 'openFunction') ?: 'main';

        $code = ' -r \'' . $this->directoryIndexCommand . '(new \\'.$type.'\Commands\\'.$command.')->'.$method.'(' . Parameters::convert($this->parameters) . ');\'';

        $this->parameters = [];

        return $this->run($code);
    }

    /**
     * Path
     * 
     * @param string $path = NULL
     * 
     * @return Job
     */
    public function path(String $path = NULL)
    {
        $this->path = $path;
        
        return $this;
    }

    /**
     * Run Cron
     * 
     * @param string $cmd = NULL
     * 
     * @return int
     */
    public function run(String $cmd = NULL)
    {
        $this->createExecFileIfNotExists();

        $this->addJobToExecFile($cmd);
        
        return $this->executeCommand();
    }

    /**
     * Protected create crontab directory if not exists
     */
    protected function createCrontabDirectoryIfNotExists()
    {
        if( ! is_dir($crontabDirectory = pathinfo($this->crontabCommands, PATHINFO_DIRNAME)) )
        {
            Filesystem::createFolder($crontabDirectory);
        }
    }

    /**
     * Protected execute command
     */
    protected function executeCommand()
    {
        return $this->processor->exec('crontab ' . $this->crontabCommands);
    }

    /**
     * Protected execute remove command
     */
    protected function executeRemoveCommand()
    {
        $this->processor->exec('crontab -r');
    }

    /**
     * Protected create exec file if not exists
     */
    protected function createExecFileIfNotExists()
    {
        if( ! is_file($this->crontabCommands) )
        {
            Filesystem\Forge::create($this->crontabCommands);

            $this->processor->exec('chmod 0777 ' . $this->crontabCommands);
        }
    }

    /**
     * Protected add job to exec file
     */
    protected function addJobToExecFile($cmd)
    {
        $content = file_get_contents($this->crontabCommands);

        if( ! stristr($content, $cmd))
        {
            $content = $content . $this->getValidCommand() . $cmd . EOL;

            file_put_contents($this->crontabCommands, $content);
        }
    }   

    /**
     * Protected remove job from exec file
     */
    protected function removeJobFromExecFile($cmd)
    {
        $jobs = $this->listArray();

        unset($jobs[$cmd]);

        file_put_contents($this->crontabCommands, implode(EOL, $jobs) . EOL);
    }

    /**
     * Get job id from exec file with term
     */
    public function getJobIdFromExecFileWithTerm($class, $method)
    {
        $jobs = [];

        foreach( $this->listArray() as $key => $job )
        {
            if( stristr($job, $class) && stristr($job, $method) )
            {
                return $key;
            }
        }

        return false;
    }

    /**
     * Protected remove job from exec file with term
     */
    protected function removeJobFromExecFileWithTerm($cmd)
    {
        $jobs = [];

        foreach( $this->listArray() as $key => $job )
        {
            if( ! stristr($job, $cmd) )
            {
                $jobs[$key] = $job;
            }
        }

        file_put_contents($this->crontabCommands, implode(EOL, $jobs) . EOL);
    }

    /**
     * Protected Zerocore
     */
    protected function getDirectoryIndexCommand()
    {
        return 'define("CONSOLE_ENABLED", true); require "'.DIRECTORY_INDEX.'"; ';
    }

    /**
     * Protected get project command
     */
    protected function getProjectCommand($value)
    {
        $this->directoryIndexCommand = 'chdir("'.REAL_BASE_DIR.'"); define("CONSOLE_PROJECT_NAME", "'.$this->user.'"); ' . $value;
    }

    /**
     * Protected convert file name
     */
    protected function convertFileName($file)
    {
        return str_replace(['/', ':'], '-', $file);
    }

    /**
     * Protected datet time format
     */
    protected function getDatetimeFormat()
    {
        if( $this->interval !== '* * * * *' )
        {
            $interval = $this->interval.' ';
        }
        else
        {
            $interval = ( $this->minute    ?? '*' ) . ' '.
                        ( $this->hour      ?? '*' ) . ' '.
                        ( $this->dayNumber ?? '*' ) . ' '.
                        ( $this->month     ?? '*' ) . ' '.
                        ( $this->day       ?? '*' ) . ' ';
        }

        $this->defaultIntervalVariables();

        return $interval;
    }

    /**
     * Protected Command
     */
    protected function getValidCommand()
    {
        $datetimeFormat = $this->getDatetimeFormat();
        $type           = $this->type;
        $path           = $this->path;
        $command        = $this->command;
        $debug          = $this->debug;

        $match = '(\*|[0-9]{1,2}|\*\/[0-9]{1,2}|[0-9]{1,2}\s*\-\s*[0-9]{1,2}|(([0-9]{1,2})*\s*\,\s*[0-9]{1,2})+)\s+';

        if( ! preg_match('/^'.$match.$match.$match.$match.$match.'$/', $datetimeFormat) )
        {
            throw new InvalidTimeFormatException('Services', 'crontab:timeFormatError');
        }
        else
        {
            return $datetimeFormat.
                   ( ! empty($path)    ? $path    . ' ' : '' ).
                   ( ! empty($command) ? $command . ' ' : '' ).
                   ( ! empty($type)    ? $type    . ' ' : '' ).
                   ( $debug === true   ? '>> '    . $this->crontabDir . 'debug.log 2>&1' : '' );
        }
    }

    /**
     * Protected defaul command variables
     */
    protected function defaultCommandVariables()
    {
        $this->type       = NULL;
        $this->path       = NULL;
        $this->command    = NULL;
        $this->debug      = false;
    }

    /**
     * Protected default interval variables
     */
    protected function defaultIntervalVariables()
    {
        $this->interval  = '* * * * *';
        $this->minute    = '*';
        $this->hour      = '*';
        $this->dayNumber = '*';
        $this->month     = '*';
        $this->day       = '*';
    }
}
