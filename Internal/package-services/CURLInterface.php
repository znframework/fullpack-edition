<?php namespace ZN\Services;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface CURLInterface
{
    /**
     * Init
     * 
     * @param string $url = NULL
     * 
     * @return CURL
     */
    public function init(String $url = NULL) : CURL;

    /**
     * Execute
     * 
     * @return mixed|false
     */
    public function exec();

    /**
     * Escape
     * 
     * @param string $str
     * 
     * @return String
     */
    public function escape(String $str) : String;

    /**
     * Unescape
     * 
     * @param string $str
     * 
     * @return string
     */
    public function unescape(String $str) : String;

    /**
     * Info
     * 
     * @param string $opt = NULL
     * 
     * @return mixed
     */
    public function info(String $opt = NULL);

    /**
     * Error
     * 
     * @return string
     */
    public function error() : String;

    /**
     * Error Number
     * 
     * @return int
     */
    public function errno() : Int;

    /**
     * Pause
     * 
     * @param string|int $bitmask = 0
     * 
     * @return string
     */
    public function pause($bitmask = 0) : Int;

    /**
     * Reset
     * 
     * @return bool
     */
    public function reset() : Bool;

    /**
     * Option
     * 
     * @param string $options
     * @param mixed  $value
     * 
     * @return CURL
     */
    public function option(String $options, $value) : CURL;

    /**
     * Close
     * 
     * @return bool
     */
    public function close() : Bool;

    /**
     * Error Value
     * 
     * @param int $errno = 0
     * 
     * @return string
     */
    public function errval(Int $errno = 0) : String;

    /**
     * Version
     * 
     * @param string $data = NULL
     * 
     * @return array|string|false
     */
    public function version($data = NULL);
}
