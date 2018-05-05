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

use Crontab;
use ZN\IS;

/**
 * @command run-cron
 * @description run-cron command:method func param func param ...\n
 */
class Cron
{
    /**
     * Magic constructor
     * 
     * @param string $command
     * @param array  $parameters
     * 
     * @return void
     */
    public function __construct($command, $parameters)
    {   
        for( $index = 0, $rindex = 1; $index < count($parameters); $index += 2, $rindex += 2 )
        {
            $func = $parameters[$index]  ?? NULL;
            $prm  = $parameters[$rindex] ?? NULL;
            Crontab::$func($prm);
        }
        
        if( IS::url($command) ) # wget
        {
            $status = Crontab::wget($command);
        }
        elseif( strstr($command, '/') )
        {
            $status = Crontab::controller($command); # controller
        }
        else
        {
            $status = Crontab::command($command); # command
        }

        new Result($status);
    }
}