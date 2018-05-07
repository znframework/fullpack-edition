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
 * @command extract-butcher
 * @description extract-butcher [all|{name}] [title|lower|slug|normal|{name}]
 */
class ExtractButcher
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
        new Result((new Butcher)->extract($command ?? 'all', $parameters[0] ?? 'title'));
    }
}