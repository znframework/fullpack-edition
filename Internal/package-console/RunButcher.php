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
 * @command run-butcher
 * @description run-butcher [theme-directory-name] [project|external]
 */
class RunButcher
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
        new Result((new Butcher)->run($command ?? 'Default', $parameters[0] ?? 'project'));
    }
}