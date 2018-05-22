<?php namespace ZN\Ability;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Config;
use ZN\Classes;

trait Configurable
{
    /**
     * Magic call static
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        $config = self::getOnlyClassName();

        array_unshift($parameters, $method);

        if( is_array($parameters[0] ?? NULL) || count($parameters) >= 2 )
        {
            return Config::set($config, ...$parameters);
        }
        
        return Config::get($config, ...$parameters);
    }

    /**
     * Get all config
     * 
     * @return array
     */
    public static function all() : Array
    {
        return Config::get(self::getOnlyClassName());
    }

    /**
     * Protected get only class name
     */
    protected static function getOnlyClassName()
    {
        return Classes::onlyName(__CLASS__);
    }
}
