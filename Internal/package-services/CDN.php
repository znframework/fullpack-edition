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

use ZN\Base;
use ZN\Config;
use ZN\Singleton;

class CDN implements CDNInterface
{  
    /**
     * Api address
     * 
     * @var string
     */
    protected static $address = 'https://api.cdnjs.com/libraries';

    /**
     * Api
     * 
     * @param string $uri
     * 
     * @return object
     */
    public static function api(String $uri)
    {
        $result = Singleton::class('ZN\Services\Restful')->get(self::$address . $uri);

        return $result;
    }

    /**
     * Get Library
     * 
     * @param string $library
     * 
     * @return object
     */
    public static function getLibrary(String $library)
    {
        return self::api(Base::prefix($library));
    }

    /**
     * Get Library
     * 
     * @param string $query
     * 
     * @return object
     */
    public static function searchQuery(String $query)
    {
        return self::api(Base::prefix($query, '?'));
    }

    /**
     * Get cdn data.
     * 
     * @param string $configName
     * @param string $name
     * 
     * @return string
     */
    public static function get(String $configName, String $name) : String
    {
        $config = Config::default('ZN\Services\CDNDefaultConfiguration')
                        ::get('CDNLinks');

        $configData = ! empty($config[$configName]) ? $config[$configName] : '';

        if( empty($configData) )
        {
            return false;
        }

        $data = array_change_key_case($configData);
        $name = strtolower($name);

        if( isset($data[$name]) )
        {
            return $data[$name];
        }
        else
        {
            return $data;
        }
    }

    /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function image(String $name) : String
    {
        return self::get('images', $name);
    }

    /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function style(String $name) : String
    {
        return self::get('styles', $name);
    }

    /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function script(String $name) : String
    {
        return self::get('scripts', $name);
    }

    /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function font(String $name) : String
    {
        return self::get('fonts', $name);
    }

   /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function file(String $name) : String
    {
        return self::get('files', $name);
    }
}
