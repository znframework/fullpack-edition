<?php namespace ZN\Prompt;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface ProcessorInterface
{
    /**
     * Path
     * 
     * @param string $path = NULL
     * 
     * @return Processor
     */
    public function path(String $path = NULL);

    /**
     * Sapi Name
     * 
     * @return string
     */
    public function type() : String;

    /**
     * Execute
     * 
     * @param string $command
     * 
     * @return string|false
     */
    public function exec($command);

    /**
     * Select Driver
     * 
     * @param string $driver
     * 
     * @return Processor
     */
    public function driver(String $driver) : Processor;

    /**
     * Output
     * 
     * @return array
     */
    public function output() : Array;

    /**
     * Return
     * 
     * @return int
     */
    public function return() : Int;
}
