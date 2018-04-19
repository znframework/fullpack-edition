<?php namespace ZN\Console;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Helper;

class Run
{
    /**
     * Default Project Name
     * 
     * @var string
     */
    protected static $project = DEFAULT_PROJECT;

    /**
     * Keep command
     * 
     * @var string
     */
    protected static $command;

    /**
     * Keep parameters
     * 
     * @var array
     */
    protected static $parameters;

    /**
     * Run commands
     * 
     * @param array $commands
     * 
     * @return void
     */
    public function __construct($commands)
    {
        Helper::report('TerminalCommands', implode(' ', $commands), 'TerminalCommands'); 

        array_shift($commands);

        if( ($commands[0] ?? NULL) !== 'project-name' )
        {
            array_unshift($commands, DEFAULT_PROJECT);
            array_unshift($commands, 'project-name');
        }

        $realCommands = implode(' ', self::arrayRemoveFirst($commands, 2));

        self::$project = $commands[1] ?? DEFAULT_PROJECT;
        $command       = $commands[2] ?? NULL;
        self::$command = $commands[3] ?? NULL;

        if( $command === NULL )
        {
            new CommandList; exit;
        }

        self::$parameters = self::arrayRemoveFirst($commands, 4);

        switch( $command )
        {
            case 'run-uri'               :
            case 'run-controller'        : new Controller(self::$command); break;
            case 'run-model'             : 
            case 'run-class'             : new Library(self::$command, self::$parameters); break;
            case 'run-cron'              : new Cron(self::$command, self::$parameters); break;
            case 'cron-list'             : new CronList; break;
            case 'remove-cron'           : new RemoveCron(self::$parameters); break;
            case 'run-command'           : new Library(self::$command, self::$parameters, PROJECT_COMMANDS_NAMESPACE); break;
            case 'run-external-command'  : new Library(self::$command, self::$parameters, EXTERNAL_COMMANDS_NAMESPACE); break;
            case 'run-function'          : new Method(self::$command, self::$parameters); break;
            case 'start-restoration'     : new StartRestoration(self::$command, self::$parameters); break;
            case 'end-restoration'       : new EndRestoration(self::$command); break;
            case 'end-restoration-delete': new EndRestorationDelete(self::$command); break;
            case 'create-project'        : new CreateProject(self::$command); break;
            case 'delete-project'        : new DeleteProject(self::$command); break;
            case 'create-controller'     : new CreateController(self::$command); break;
            case 'create-grand-model'    : new CreateGrandModel(self::$command); break;
            case 'create-grand-vision'   : new CreateGrandVision(self::$command); break;
            case 'delete-grand-vision'   : new DeleteGrandVision(self::$command); break;
            case 'create-model'          : new CreateModel(self::$command); break;
            case 'delete-controller'     : new DeleteController(self::$command); break;
            case 'delete-model'          : new DeleteModel(self::$command); break;
            case 'clean-cache'           : new CleanCache; break;
            # 5.3.5[added]
            case 'generate-databases'    : new GenerateDatabases; break;
            case 'command-list'          : new CommandList; break;
            default                      :
            # 5.3.5[added]
            if( strstr($realCommands, ':') )
            {
                new ShortCommand($realCommands);
            }
            else
            {
                new TerminalCommand($realCommands);
            }
        }
    }

    /**
     * Array Remove First
     * 
     * @param array & $array
     * @param int     $count = 1
     * 
     * @return array
     * 
     */
    protected static function arrayRemoveFirst(Array $array, Int $count = 1) : Array
    {
        for( $i = 0; $i < $count; $i++ )
        {
            array_shift($array);
        }

        return $array;
    }
}
