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

use ZN\ErrorHandling\Errors;

class Support
{
    /**
     * Function
     * 
     * @param string $name
     * @param string $value
     * 
     * @return bool
     */
    public static function function(string $name, string $value = NULL)
    {
        return self::callback($name, $value);
    }

     /**
     * Func
     * 
     * @param string $name
     * @param string $value
     * 
     * @return bool
     */
    public static function func(string $name, string $value = NULL)
    {
        return self::_loaded($name, $value, 'function_exists', 'undefinedFunctionExtension');
    }

     /**
     * Callback
     * 
     * @param string $name
     * @param string $value
     * 
     * @return bool
     */
    public static function callback(string $name, string $value = NULL)
    {
        return self::_loaded($name, $value, 'function_exists', 'undefinedFunction');
    }

     /**
     * Extension
     * 
     * @param string $name
     * @param string $value
     * 
     * @return bool
     */
    public static function extension(string $name, string $value = NULL)
    {
        return self::_loaded($name, $value, 'extension_loaded', 'undefinedFunctionExtension');
    }

     /**
     * Library
     * 
     * @param string $name
     * @param string $value
     * 
     * @return bool
     */
    public static function library(string $name, string $value = NULL)
    {
        return self::_loaded($name, $value, 'class_exists', 'classError');
    }

     /**
     * Writable
     * 
     * @param string $name
     * @param string $value
     * 
     * @return bool
     */
    public static function writable(string $name, string $value = NULL)
    {
        return self::_loaded($name, $value, 'is_writable', 'fileNotWrite');
    }

     /**
     * Driver
     * 
     * @param array  $drivers
     * @param string $driver = NULL
     * 
     * @return bool
     */
    public static function driver(array $drivers, string $driver = NULL)
    {
        if( ! in_array(strtolower($driver), $drivers) )
        {
            throw new Exception('Error', 'driverError', $driver);
        }

        return true;
    }

     /**
     * Class Method
     * 
     * @param string $class
     * @param string $value
     * 
     * @return bool
     */
    public static function classMethod(string $class, string $method)
    {
        throw new Exception
        (
            'Error',
            'undefinedFunction',
            Datatype::divide(str_ireplace(INTERNAL_ACCESS, '', Classes::onlyName($class)), '\\', -1)."::$method()"
        );
    }

    /**
     * Protected Loaded
     * 
     * @param string $name
     * @param string $value
     * @param string $function
     * @param mixed  $error
     * 
     * @return bool
     */
    protected static function _loaded($name, $value, $func, $error)
    {
        if( empty($value) )
        {
            $value = ucfirst($name);
        }

        if( ! $func($name) )
        {
            if( is_string($error) )
            {
                throw new Exception('Error', $error, $value);
            }
            else
            {
                throw new Exception(key($error), current($error), $value);
            }
        }

        return false;
    }
}
