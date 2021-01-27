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

use ZN\Lang;
use ZN\Classes;

trait Speech
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

        return Lang::default('ZN\CoreDefaultLanguage')::select($config, ...$parameters);
    }

    /**
     * Get all config
     * 
     * @return array
     */
    public static function all() : Array
    {
        return Lang::default('ZN\CoreDefaultLanguage')::select(self::getOnlyClassName());
    }

    /**
     * Protected get only class name
     */
    protected static function getOnlyClassName()
    {
        return Classes::onlyName(__CLASS__);
    }
}
