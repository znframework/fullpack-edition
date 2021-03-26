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

interface StoreInterface
{
    /**
     * Write
     * 
     * @param string $file
     * @param mixed  $data
     * 
     * @return string
     */
    public static function write(string $file, $data) : bool;

    /**
     * Read
     * 
     * @param string $file
     * @param bool   $array = false
     * 
     * @return mixed
     */
    public static function read(string $file, bool $array = false);

    /**
     * Read object
     * 
     * @param string $file
     * 
     * @return object
     */
    public static function readObject(string $file);

    /**
     * Read array
     * 
     * @param string $file
     * 
     * @return array
     */
    public static function readArray(string $file) : array;

    /**
     * Encode
     * 
     * @param mixed  $data
     * @param string $type = 'unescapedUnicode'
     * 
     * @return string
     */
    public static function encode($data) : string;

    /**
     * Decode
     * 
     * @param string $data
     * @param bool   $array  = false
     * @param int    $length = 512
     * 
     * @return mixed
     */
    public static function decode(string $data);

    /**
     * Decode Object
     * 
     * @param string $data
     * 
     * @return object
     */
    public static function decodeObject(string $data);

   /**
     * Decode Array
     * 
     * @param string $data
     * 
     * @return array
     */
    public static function decodeArray(string $data) : array;

    /**
     * Error
     * 
     * @param void
     * 
     * @return string
     */
    public static function error() : string;
    
    /** 
     * Error No
     * 
     * @param void
     * 
     * @return int
     */
    public static function errno() : int;
    
    /** 
     * Check
     * 
     * @param string $data
     * 
     * @return bool
     */
    public static function check(string $data) : bool;
}
