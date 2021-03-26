<?php namespace ZN\DataTypes\Arrays;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Datatype;

class Casing
{
    /**
     * Case Array
     * 
     * @param array  $array
     * @param string $type   - options[lower|upper|title]
     * @param string $keyval - options[all|key|value]
     * 
     * @return array
     */
    public static function use(array $array, string $type = 'lower', string $keyval = 'all') : array
    {
        return Datatype::caseArray($array, $type, $keyval);
    }

    /**
     * Lower Keys
     * 
     * @param array  $array
     * 
     * @return array
     */
    public static function lowerKeys(array $array) : array
    {
        return array_change_key_case($array);
    }

    /**
     * Title Keys
     * 
     * @param array  $array
     * 
     * @return array
     */
    public static function titleKeys(array $array) : array
    {
        return self::use($array, 'title', 'key');
    }

    /**
     * Upper Keys
     * 
     * @param array  $array
     * 
     * @return array
     */
    public static function upperKeys(array $array) : array
    {
        return array_change_key_case($array, CASE_UPPER);
    }

    /**
     * Lower Values
     * 
     * @param array  $array
     * 
     * @return array
     */
    public static function lowerValues(array $array) : array
    {
        return self::use($array, 'lower', 'value');
    }

    /**
     * Title Values
     * 
     * @param array  $array
     * 
     * @return array
     */
    public static function titleValues(array $array) : array
    {
        return self::use($array, 'title', 'value');
    }

    /**
     * Upper Values
     * 
     * @param array  $array
     * 
     * @return array
     */
    public static function upperValues(array $array) : array
    {
        return self::use($array, 'upper', 'value');
    }

    /**
     * All Lower Case
     * 
     * @param array  $array
     * 
     * @return array
     */
    public static function lower(array $array) : array
    {
        return self::use($array, 'lower', 'all');
    }

    /**
     * All Title Case
     * 
     * @param array  $array
     * 
     * @return array
     */
    public static function title(array $array) : array
    {
        return self::use($array, 'title', 'all');
    }

    /**
     * All Upper Case
     * 
     * @param array  $array
     * 
     * @return array
     */
    public static function upper(array $array) : array
    {
        return self::use($array, 'upper', 'all');
    }
}
