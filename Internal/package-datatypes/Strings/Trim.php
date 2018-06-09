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

use ZN\Base;

class Trim
{
    /**
     * Middle
     * 
     * @param string $str
     * 
     * @return string
     */
    public static function middle(String $str) : String
    {
        $str = preg_replace
        (
            ['/\s+/', '/&nbsp;/', "/\n/", "/\r/", "/\t/"],
            ['', '', '', '', ''],
            $str
        );

        return $str;
    }

    /**
     * Slashes
     * 
     * @param string $str
     * 
     * @return string
     */
    public static function slashes(String $str) : String
    {
        $str = trim($str, "/");

        return $str;
    }

    /**
     * Removes an expression in begin of a string.,
     * 
     * @param string $data 
     * @param string $fix = '/'
     * 
     * @return string
     */
    public static function removePrefix(String $data = NULL, String $fix = '/') : String
    {
        return Base::removePrefix($data, $fix);
    }

    /**
     * Removes an expression in begin of a string.,
     * 
     * @param string $data 
     * @param string $fix = '/'
     * 
     * @return string
     */
    public static function removeSuffix(String $data = NULL, String $fix = '/') : String
    {
        return Base::removeSuffix($data, $fix);
    }

    /**
     * It removes an expression from both sides of a string.
     * 
     * @param string $data 
     * @param string $fix = '/'
     * 
     * @return string
     */
    public static function removePresuffix(String $data = NULL, String $fix = '/') : String
    {
        return Base::removePresuffix($data, $fix);
    }

    /**
     * suffix 
     * 
     * It is used to append a suffix to any string.
     * 
     * @param string = NULL
     * @param string = $fix = '/'
     * 
     * @return string
     */
    public static function suffix(String $data = NULL, String $fix = '/') : String
    {
        return Base::suffix($data, $fix);
    }

    /**
     * prefix 
     * 
     * It is used to append a prefix to any string.
     * 
     * @param string = NULL
     * @param string = $fix = '/'
     * 
     * @return string
     */
    public static function prefix(String $data = NULL, String $fix = '/') : String
    {
        return Base::prefix($data, $fix);
    }

    /**
     * prefix 
     * 
     * Used to append both suffixes and prefixes to any string.
     * 
     * @param string = NULL
     * @param string = $fix = '/'
     * 
     * @return string
     */
    public static function presuffix(String $data = NULL, String $fix = '/') : String
    {
        return Base::presuffix($data, $fix);
    }
}
