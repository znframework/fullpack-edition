<?php namespace ZN\Request;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface HttpInterface
{
    /**
     * Response
     * 
     * @param int    $code
     * @param string $version = '1.1'
     */
    public static function response(int $code, string $version = '1.1');

    /**
     * Fix
     * 
     * @param bool $security = false
     * 
     * @return string
     */
    public static function fix(bool $security = false) : string;

    /**
     * Host
     * 
     * @return string
     */
    public static function host() : string;

    /**
     * User Agent
     * 
     * @return string
     */
    public static function userAgent() : string;

    /**
     * Accept
     * 
     * @return string
     */
    public static function accept() : string;

    /**
     * Language
     * 
     * @return string
     */
    public static function language() : string;

    /**
     * Encoding
     * 
     * @return string
     */
    public static function encoding() : string;

    /**
     * Cookie
     * 
     * @return string
     */
    public static function cookie() : string;

    /**
     * Connection
     * 
     * @return string
     */
    public static function connection() : string;

    /**
     * Request method type
     * 
     * @param string ...$methods
     * 
     * @returm bool
     */
    public static function isRequestMethod(...$methods) : bool;

    /**
     * Request is ajax
     * 
     * @param bool
     */
    public static function isAjax() : bool;
    
    /**
     * Get Browser Lang
     * 
     * @param string $default = 'en'
     * 
     * @return string
     */
    public static function browserLang(string $default = 'en') : string;

    /**
     * Code
     * 
     * @param int|string $code = 200
     * 
     * @return string
     */
    public static function code($code = 200) : string;

    /**
     * Message
     * 
     * @param string $message
     * 
     * @return string
     */
    public static function message(string $message) : string;

    /**
     * Name
     * 
     * @param string $name
     * 
     * @return Http
     */
    public static function name(string $name) : Http;

    /**
     * Value
     * 
     * @param mixed $value
     * 
     * @return Http
     */
    public static function value($value) : Http;

    /**
     * Input
     * 
     * @param string $input
     * 
     * @return Http
     */
    public static function input(string $input) : Http;

    /**
     * Select
     * 
     * @param string $name
     * 
     * @return mixed
     */
    public static function select(string $name = NULL);

    /**
     * Insert
     * 
     * @param string $name  = NULL
     * @param mixed  $value = NULL
     * 
     * @return bool
     */
    public static function insert(string $name = NULL, $value = NULL) : bool;

    /**
     * Insert
     * 
     * @param string $name  = NULL
     * 
     * @return bool
     */
    public static function delete(string $name = NULL) : bool;
}
