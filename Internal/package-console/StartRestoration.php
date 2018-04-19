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

use ZN\Restoration;

/**
 * @command start-restoration
 * @description start-restoration project name [full, standart or [directories]] 
 */
class StartRestoration
{
    /**
     * Magic constructor
     * 
     * @param string $command
     * @param string $parameters
     * 
     * @return void
     */
    public function __construct($command, $parameters)
    {   
        new Result(Restoration::start
        (
            $command, 
            ($parameters[0] ?? NULL) === 'full' ? 'full' : $parameters
        ));
    }
}