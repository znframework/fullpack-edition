<?php namespace ZN\Prompt;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Config;
use ZN\Buffering;

class Processor implements ProcessorInterface
{
    /**
     * @var string
     */
    protected $output;

    /**
     * @var int
     */
    protected $return;

    /**
     * @var string
     */
    protected $driver;

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        $this->getConfig = Config::default('ZN\Prompt\PromptDefaultConfiguration')::get('Services', 'processor');
        $this->driver    = $this->getConfig['driver'];
    }

    /**
     * Sapi Name
     * 
     * @return string
     */
    public function type() : String
    {
        switch( $name = substr($sapi = php_sapi_name(), 0, 3) )
        {
            case 'cli' : 
            case 'cgi' : return $name;

            default    : return $sapi;
        }
    }

    /**
     * Execute
     * 
     * @param string $command
     * 
     * @return string|false
     */
    public function exec($command)
    {
        switch( $this->driver )
        {
            case 'exec':
                $return = exec($command, $this->output, $this->return);
            break;

            case 'shell_exec':
            case 'shell'     :
                $return       = shell_exec($command);
                $this->output = $this->_split($return);
                $this->return = 0;
            break;

            case 'system':
                $return       = Buffering\Callback::do(function() use($command) {system($command, $this->return);});
                $this->output = $this->_split($return);
            break;
        }

        $this->driver = NULL;

        return $return ?? false;
    }

    /**
     * Select Driver
     * 
     * @param string $driver
     * 
     * @return Processor
     */
    public function driver(String $driver) : Processor
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Output
     * 
     * @return array
     */
    public function output() : Array
    {
        return (array) $this->output;
    }

    /**
     * Return
     * 
     * @return int
     */
    public function return() : Int
    {
        return (int) $this->return;
    }

    /**
     * Protected Split
     */
    protected function _split($string)
    {
        return explode("\n", rtrim($string, "\n"));
    }
}
