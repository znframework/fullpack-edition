<?php namespace ZN\Security;
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

class Html
{
    /**
     * Encode HTML
     * 
     * @param string $string 
     * @param string $type     = 'quotes'
     * @param string $encoding = 'utf-8'
     * 
     * @return string
     */
    public static function encode(string $string, string $type = 'quotes', string $encoding = 'utf-8') : string
    {
        return htmlspecialchars(trim($string), Helper::toConstant($type, 'ENT_'), $encoding);
    }

    /**
     * Decode HTML
     * 
     * @param string $string 
     * @param string $type     = 'quotes'
     * 
     * @return string
     */
    public static function decode(string $string, string $type = 'quotes') : string
    {
        return htmlspecialchars_decode(trim($string), Helper::toConstant($type, 'ENT_'));
    }

    /**
     * Clean HTML Tag
     * 
     * @param string $string 
     * @param mixed  $allowable = ''
     * 
     * @return string
     */
    public static function tagClean(string $string, $allowable = '') : string
    {
        return strip_tags(self::decode($string), $allowable);
    }
}
