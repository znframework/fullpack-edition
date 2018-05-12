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

use ZN\Butcher;

/**
 * @command run-butcher-delete
 * @description run-butcher-delete [theme-directory-name] [project|external]
 */
class RunButcherDelete
{
    /**
     * Magic constructor
     * 
     * @param string $command
     * 
     * @return void
     */
    public function __construct($command, $parameters)
    {   
        new Result((new Butcher)->runDelete($command ?? 'Default', $parameters[0] ?? 'project'));
    }
}