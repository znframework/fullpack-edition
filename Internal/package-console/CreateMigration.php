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

use Migration;

/**
 * @command create-migration
 * @description create-migration {migration-name} 
 */
class CreateMigration
{
    /**
     * Magic constructor
     * 
     * @param string $command
     * 
     * @return void
     */
    public function __construct($command, $parameters = [])
    {   
        new Result(Migration::create($command, $parameters[0] ?? 0));
    }
}