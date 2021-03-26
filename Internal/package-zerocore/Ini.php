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

class Ini
{
    /**
     * Magic Call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        $parts  = Datatype::splitUpperCase($method);

        $method = strtolower(implode('_', $parts));

        if( isset(self::getAll()[$method]) )
        {
            if( isset($parameters[0]) )
            {
                ini_set($method, $parameters[0]);
            }
            else
            {
                return ini_get($method);
            }
            
        }
        else
        {
            throw new Exception\InvalidArgumentException('The ['.$method.'] method is not a valid ini configuration!');
        }
    }

    /**
     * Ini set
     * 
     * @param string $setting
     * @param string $value
     * 
     * @return string
     */
    public static function set(string $setting, string $value) : string
    {
        return ini_set($setting, $value);
    }

    /**
     * Ini get
     * 
     * @param string $setting
     * 
     * @return string
     */
    public static function get(string $setting) : string
    {
        return ini_get($setting);
    }

    /**
     * Ini restore
     * 
     * @param string $setting
     */
    public static function restore(string $setting)
    {
        ini_restore($setting);
    }

    /**
     * Ini get all
     * 
     * @return array
     */
    public static function getAll() : array
    {
        return ini_get_all(NULL, false);
    }
}
