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

class Injection
{
    /**
     * Nail Chars
     * 
     * @var array
     */
    protected static $nailChars =
    [
        "'" => "&#39;",
        '"' => "&#34;"
    ];

    /**
     * Encode Injection Chars
     * 
     * @param string $string 
     * 
     * @return string
     */
    public static function encode(string $string) : string
    {
        $secBadChars = Properties::$injectionBadChars;

        if( ! empty($secBadChars) )
        {
            foreach( $secBadChars as $badChar => $changeChar )
            {
                $badChar = trim($badChar, '/');
                $string  = preg_replace('/'.$badChar.'/xi', $changeChar, $string);
            }
        }

        return addslashes(trim($string));
    }

    /**
     * Decode Injection Chars
     * 
     * @param string $string 
     * 
     * @return string
     */
    public static function decode(string $string) : string
    {
        return stripslashes(trim($string));
    }

    /**
     * Encode Nail Chars
     * 
     * @param string $string 
     * 
     * @return string
     */
    public static function nailEncode(string $str) : string
    {
        $str = str_replace(array_keys(self::$nailChars), array_values(self::$nailChars), $str);

        return $str;
    }

    /**
     * Decode Nail Chars
     * 
     * @param string $string 
     * 
     * @return string
     */
    public static function nailDecode(string $str) : string
    {
        $str = str_replace(array_values(self::$nailChars), array_keys(self::$nailChars), $str);

        return $str;
    }

    /**
     * Encode Escape String
     * 
     * @param string $string 
     * 
     * @return string
     */
    public static function escapeStringEncode(string $data) : string
    {
        return addslashes($data);
    }

    /**
     * Decode Escape String
     * 
     * @param string $string 
     * 
     * @return string
     */
    public static function escapeStringDecode(string $data) : string
    {
        return stripslashes($data);
    }
}
