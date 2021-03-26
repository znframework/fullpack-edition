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
     * Multiple
     * 
     * @param callback $callback
     * 
     * @return mixed
     */
    public function multiple();

    /**
     * Init
     * 
     * @param string $url = NULL
     * 
     * @return CURL
     */
    public function init(string $url = NULL) : CURL;

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
    public function escape(string $str) : string;

    /**
     * Unescape
     * 
     * @param string $str
     * 
     * @return string
     */
    public function unescape(string $str) : string;

    /**
     * Info
     * 
     * @param string $opt = NULL
     * 
     * @return mixed
     */
    public function info(string $opt = NULL);

    /**
     * Error
     * 
     * @return string
     */
    public function error() : string;

    /**
     * Error Number
     * 
     * @return int
     */
    public function errno() : int;

    /**
     * Pause
     * 
     * @param string|int $bitmask = 0
     * 
     * @return string
     */
    public function pause($bitmask = 0) : int;

    /**
     * Reset
     * 
     * @return bool
     */
    public function reset() : bool;

    /**
     * Option
     * 
     * @param string $options
     * @param mixed  $value
     * 
     * @return CURL
     */
    public function option(string $options, $value) : CURL;

    /**
     * Close
     * 
     * @return bool
     */
    public function close() : bool;

    /**
     * Error Value
     * 
     * @param int $errno = 0
     * 
     * @return string
     */
    public function errval(int $errno = 0) : string;

    /**
     * Version
     * 
     * @param string $data = NULL
     * 
     * @return array|string|false
     */
    public function version($data = NULL);
}
