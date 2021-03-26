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
    public static function middle(string $str) : string
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
    public static function slashes(string $str) : string
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
    public static function removePrefix(string $data = NULL, string $fix = '/') : string
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
    public static function removeSuffix(string $data = NULL, string $fix = '/') : string
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
    public static function removePresuffix(string $data = NULL, string $fix = '/') : string
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
    public static function suffix(string $data = NULL, string $fix = '/') : string
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
    public static function prefix(string $data = NULL, string $fix = '/') : string
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
    public static function presuffix(string $data = NULL, string $fix = '/') : string
    {
        return Base::presuffix($data, $fix);
    }
}
