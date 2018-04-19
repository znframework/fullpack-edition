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

/**
 * @command remove-cron
 * @description remove-cron cronID  
 */
class RemoveCron
{
    /**
     * Magic constructor
     * 
     * @param array $parameters
     * 
     * @return void
     */
    public function __construct($parameters)
    {   
        new Result(Crontab::remove($parameters ?? NULL));
    }
}