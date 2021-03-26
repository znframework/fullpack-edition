<?php namespace ZN\Protection;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use stdClass;

class Serial extends StoreAbstract implements StoreInterface
{
    use StoreTrait;
    
    /**
     * Encode
     * 
     * @param mixed  $data
     * 
     * @return string
     */
    public static function encode($data) : string
    {
        return serialize($data);
    }

    /**
     * Decode
     * 
     * @param string $data
     * @param bool   $array = false
     * 
     * @return object
     */
    public static function decode(string $data, bool $array = false)
    {
        if( $array === false )
        {
            return (object) unserialize($data);
        }
        else
        {
            return (array) unserialize($data);
        }
    }

    /**
     * Decode
     * 
     * @param string $data
     * @param bool   $array = false
     * 
     * @return object
     */
    public static function decodeObject(string $data) : stdClass
    {
        return self::decode($data, false);
    }

    /**
     * Decode Array
     * 
     * @param string $data
     * @param bool   $array = false
     * 
     * @return array
     */
    public static function decodeArray(string $data) : array
    {
        return self::decode($data, true);
    }
}
