<?php namespace ZN\ErrorHandling;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface ExceptionsInterface
{
    /**
     * Throw exception
     * 
     * @param string $message = NULL
     * @param string $key     = NULL
     * @param mixed  $send    = NULL
     * 
     * @return void
     */
    public static function throws(string $message = NULL, string $key = NULL, $send = NULL);

    /**
     * Get exception table
     * 
     * @param mixed  $no    = NULL
     * @param string $msg   = NULL
     * @param string $file  = NULL
     * @param string $line  = NULL
     * @param array  $trace = NULL
     * 
     * @return void
     */
    public static function table($no = NULL, string $msg = NULL, string $file = NULL, string $line = NULL, array $trace = NULL);

    /**
     * Restore exception
     * 
     * @param void
     * 
     * @return bool
     */
    public static function restore() : bool;

    /**
     * Set exception handler
     * 
     * @param void
     * 
     * @return void
     */
    public static function handler();
}
