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
     * Fix
     * 
     * @param bool $security = false
     * 
     * @return string
     */
    public static function fix(Bool $security = false) : String;

    /**
     * Host
     * 
     * @return string
     */
    public static function host() : String;

    /**
     * User Agent
     * 
     * @return string
     */
    public static function userAgent() : String;

    /**
     * Accept
     * 
     * @return string
     */
    public static function accept() : String;

    /**
     * Language
     * 
     * @return string
     */
    public static function language() : String;

    /**
     * Encoding
     * 
     * @return string
     */
    public static function encoding() : String;

    /**
     * Cookie
     * 
     * @return string
     */
    public static function cookie() : String;

    /**
     * Connection
     * 
     * @return string
     */
    public static function connection() : String;

    /**
     * Request method type
     * 
     * @param string ...$methods
     * 
     * @returm bool
     */
    public static function isRequestMethod(...$methods) : Bool;

    /**
     * Request is ajax
     * 
     * @param bool
     */
    public static function isAjax() : Bool;

    /**
     * Request CURL
     * 
     * @return bool
     */
    public static function isCurl() : Bool;

    /**
     * Get Browser Lang
     * 
     * @param string $default = 'en'
     * 
     * @return string
     */
    public static function browserLang(String $default = 'en') : String;

    /**
     * Code
     * 
     * @param int|string $code = 200
     * 
     * @return string
     */
    public static function code($code = 200) : String;

    /**
     * Message
     * 
     * @param string $message
     * 
     * @return string
     */
    public static function message(String $message) : String;

    /**
     * Name
     * 
     * @param string $name
     * 
     * @return Http
     */
    public static function name(String $name) : Http;

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
    public static function input(String $input) : Http;

    /**
     * Select
     * 
     * @param string $name
     * 
     * @return mixed
     */
    public static function select(String $name = NULL);

    /**
     * Insert
     * 
     * @param string $name  = NULL
     * @param mixed  $value = NULL
     * 
     * @return bool
     */
    public static function insert(String $name = NULL, $value = NULL) : Bool;

    /**
     * Insert
     * 
     * @param string $name  = NULL
     * 
     * @return bool
     */
    public static function delete(String $name = NULL) : Bool;
}
