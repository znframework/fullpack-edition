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

/**
 * @command run-function
 * @description run-function function p1 p2 ... pN
 */
class Method
{
    /**
     * Magic constructor
     * 
     * @param string $method
     * @param string $parameters
     * 
     * @return void
     */
    public function __construct($method, $parameters)
    {   
        new Result($method(...$parameters));
    }
}