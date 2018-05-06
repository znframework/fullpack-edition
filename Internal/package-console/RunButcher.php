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
    public function __construct($command, $parametre)
    {   
        new Result((new Butcher)->run($command ?? 'Default', $parametre[0] ?? 'project'));
    }
}