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

use Generate;

/**
 * @command create-controller
 * @description create-controller controller name 
 */
class CreateController
{
    /**
     * Magic constructor
     * 
     * @param string $command
     * 
     * @return void
     */
    public function __construct($command)
    {   
        new Result(Generate::controller($command,
        [
            'extends'   => 'Controller',
            'namespace' => 'Project\Controllers',
            'functions' => ['main']
        ]));
    }
}