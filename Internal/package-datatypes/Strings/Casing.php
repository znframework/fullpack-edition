<?php namespace ZN\DataTypes\Strings;
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

class Casing
{
    /**
     * Casing
     * 
     * @param string $str
     * @param string $type     = 'lower'
     * @param string $encoding = 'utf-8'
     * 
     * @return string
     */
    public static function use(string $str, string $type = 'lower', string $encoding = 'utf-8') : string
    {
        return mb_convert_case($str, Helper::toConstant($type, 'MB_CASE_'), $encoding);
    }

    /**
     * Upper
     * 
     * @param string $str
     * @param string $encoding = 'utf-8'
     * 
     * @return string
     */
    public static function upper(string $str, string $encoding = 'utf-8') : string
    {
        return self::use($str, __FUNCTION__, $encoding);
    }

    /**
     * Lower
     * 
     * @param string $str
     * @param string $encoding = 'utf-8'
     * 
     * @return string
     */
    public static function lower(string $str, string $encoding = 'utf-8') : string
    {
        return self::use($str, __FUNCTION__, $encoding);
    }

    /**
     * Title
     * 
     * @param string $str
     * @param string $encoding = 'utf-8'
     * 
     * @return string
     */
    public static function title(string $str, string $encoding = 'utf-8') : string
    {
        return self::use($str, __FUNCTION__, $encoding);
    }

    /**
     * Camel
     * 
     * @param string $str
     * 
     * @return string
     */
    public static function camel(string $str) : string
    {
        $string = self::title(trim($str));

        $string[0] = self::lower($string);

        return Trim::middle($string);
    }

    /**
     * Pascal
     * 
     * @param string $str
     * 
     * @return string
     */
    public static function pascal(string $str) : string
    {
        $string = self::title(trim($str));

        return Trim::middle($string);
    }

    /**
     * Underscore
     * 
     * @param string $str
     * 
     * @return string
     */
    public static function underscore(string $str) : string
    {
        return preg_replace('/(\s+)/', '_', strtolower($str));
    }
}
