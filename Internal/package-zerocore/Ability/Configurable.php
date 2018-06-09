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
        # Class name information without a namespace.
        $config = self::getOnlyClassName();

        # The called method name is accepted as the first parameter.
        array_unshift($parameters, $method);

        # If it contains at least 2 or 3 parameters, 
        # it means that reconfiguration is being performed.
        if( is_array($parameters[0] ?? NULL) || count($parameters) >= 2 )
        {
            # Settings are being reconfigured.
            return Config::set($config, ...$parameters);
        }
        
        # If no reconfiguration condition is found, the current settings are returned.
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
