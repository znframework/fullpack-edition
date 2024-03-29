<?php namespace ZN;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class IS
{
    /**
     * Data Types
     * 
     * @var array
     */
    protected static $dataTypes = ['cookie', 'session', 'get', 'post', 'env', 'server'];

    /**
     * Magic Call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if( function_exists($ctype = 'ctype_' . $method) )
        {
            return $ctype((string) $parameters[0]);
        }

        # 5.3.2[edited]
        if( in_array($method, self::$dataTypes) )
        {
            return (bool) $method::{$parameters[0]}();
        }

        $methods = Datatype::splitUpperCase($realMethod = $method);
        $method  = implode('_', Datatype::caseArray($methods));
        $method  = 'is_' . $method;

        if( ! function_exists($method) )
        {
            Support::classMethod(__CLASS__, $realMethod);
        }

        return $method(...$parameters);
    }

    /**
     * Software
     * 
     * @param void
     * 
     * @return string
     */
    public static function software() : string
    {
        return strtolower(explode('/', $_SERVER['SERVER_SOFTWARE'] ?? 'apache')[0]);
    }

    /**
     * Timezone
     * 
     * @param string $timezone
     * 
     * @return bool
     */
    public static function timeZone(string $timezone) : bool
    {
        return in_array($timezone, timezone_identifiers_list());
    }

    /**
     * PHP Version
     * 
     * @param string $version = '5.2.4'
     * 
     * @return bool
     */
    public static function phpVersion(string $version = '5.2.4') : bool
    {
        return (bool) version_compare(PHP_VERSION, $version, '>=');
    }

    /**
     * Import
     * 
     * @param string $path
     * 
     * @return bool
     */
    public static function import(string $path) : bool
    {
        return in_array( realpath(Base::suffix($path, '.php')), get_required_files() );
    }

    /**
     * URL
     * 
     * @param string $url
     * 
     * @return bool
     */
    public static function url(string $url) : bool
    {
        return preg_match('#^(\w+:)?//#i', $url);
    }

    /**
     * Email
     * 
     * @param string $email
     * 
     * @return bool
     */
    public static function email(string $email) : bool
    {
        return preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email);
    }

    /**
     * Char
     * 
     * @param mixed $str
     * 
     * @return bool
     */
    public static function char($str) : bool
    {
        return is_scalar($str);
    }

    /**
     * Real Numeric
     * 
     * @param mixed $num = 0
     * 
     * @return bool
     */
    public static function realNumeric($num = 0) : bool
    {
        return ! is_string($num) && is_numeric($num);
    }

    /**
     * Declared Class
     * 
     * @param string $class
     * 
     * @return bool
     */
    public static function declaredClass(string $class) : bool
    {
        return in_array(strtolower($class), array_map('strtolower', get_declared_classes()));
    }

    /**
     * Hash
     * 
     * @param string $type
     * 
     * @return bool
     */
    public static function hash(string $type) : bool
    {
        $hashAlgos = array_merge(hash_algos(), ['super', 'golden']);

        return in_array($type, $hashAlgos);
    }

    /**
     * Charset
     * 
     * @param string $charset
     * 
     * @return bool
     */
    public static function charset(string $charset) : bool
    {
        return array_search(strtolower($charset), array_map('strtolower', mb_list_encodings()), true);
    }

    /**
     * Array
     * 
     * @param mixed $array
     * 
     * @return bool
     */
    public static function array($array) : bool
    {
        return ! empty($array) && is_array($array);
    }

    /**
     * Slug
     * 
     * @param string $slug
     * 
     * @return bool
     */
    public static function slug(string $slug) : bool
    {
        return (bool) preg_match('/^([a-z0-9]+\-*){1,}$/', $slug);
    }

    /**
     * Closure
     * 
     * @param mixed $data
     * 
     * @return bool
     */
    public static function closure($data) : bool
    {
        return ! is_string($data) && is_callable($data);
    }
}
