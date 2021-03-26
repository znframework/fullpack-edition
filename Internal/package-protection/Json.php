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

use ZN\Helper;

class Json extends StoreAbstract implements StoreInterface
{
    use StoreTrait;

    /**
     * Encode
     * 
     * @param mixed  $data
     * @param string $type = 'unescapedUnicode'
     * 
     * @return string
     */
    public static function encode($data, string $type = 'unescapedUnicode') : string
    {
        return json_encode($data, Helper::toConstant($type, 'JSON_'));
    }

    /**
     * Decode
     * 
     * @param string $data
     * @param bool   $array  = false
     * @param int    $length = 512
     * 
     * @return mixed
     */
    public static function decode(string $data, bool $array = false, int $length = 512)
    {
        $return = json_decode($data, $array, $length);

        return $return;
    }

    /**
     * Decode Object
     * 
     * @param string $data
     * @param int    $length = 512
     * 
     * @return object
     */
    public static function decodeObject(string $data, int $length = 512)
    {
        return json_decode($data, false, $length);
    }

   /**
     * Decode Array
     * 
     * @param string $data
     * @param int    $length = 512
     * 
     * @return array
     */
    public static function decodeArray(string $data, int $length = 512) : array
    {
        return (array) json_decode($data, true, $length);
    }

    /**
     * Error
     * 
     * @param void
     * 
     * @return string
     */
    public static function error() : string
    {
        return json_last_error_msg();
    }
    
    /** 
     * Error No
     * 
     * @param void
     * 
     * @return int
     */
    public static function errno() : int
    {
        return json_last_error();
    }
    
    /** 
     * Check
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function check(string $data) : bool
    {
        return ( is_array(json_decode($data, true)) && json_last_error() === 0 );
    }
}
