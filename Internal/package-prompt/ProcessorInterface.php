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
     * Sapi Name
     * 
     * @return string
     */
    public function type() : string;

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
    public function driver(string $driver) : Processor;

    /**
     * Output
     * 
     * @return array
     */
    public function output() : array;

    /**
     * Return
     * 
     * @return int
     */
    public function return() : int;
}
