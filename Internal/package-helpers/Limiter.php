<?php namespace ZN\Helpers;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Limiter
{
    /**
     * Limit word
     * 
     * @param string $str
     * @param int    $limit     = 100
     * @param string $endChar   = '...'
     * @param bool   $stripTags = true
     * @param string $encoding  = 'utf-8'
     * 
     * @return string
     */
    public static function word(string $str, int $limit = 100, string $endChar = '...', bool $stripTags = true, string $encoding = "utf-8") : string
    {
        $str = trim($str);

        if( $stripTags === true )
        {
            $str = strip_tags($str);
        }

        $str = str_replace(["\n","\r","&nbsp;"], " ", $str);

        preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

        $match = $matches[0] ?? NULL;

        if( mb_strlen($str, $encoding) === mb_strlen($match, $encoding) )
        {
            $endChar = '';
        }

        return rtrim($match ?? NULL).$endChar;
    }

    /**
     * Limit char
     * 
     * @param string $str
     * @param int    $limit     = 500
     * @param string $endChar   = '...'
     * @param bool   $stripTags = true
     * @param string $encoding  = 'utf-8'
     * 
     * @return string
     */
    public static function char(string $str, int $limit = 500, string $endChar = '...',  bool $stripTags = false, string $encoding = "utf-8") : string
    {
        $str = trim($str);

        if( $stripTags === true )
        {
            $str = strip_tags($str);
        }

        $str = preg_replace("/\s+/", ' ', str_replace(["\r\n", "\r", "\n", "&nbsp;"], ' ', $str));

        if( mb_strlen($str, $encoding) <= $limit )
        {
            return $str;
        }
        else
        {
            return mb_substr($str, 0, $limit, $encoding).$endChar;
        }
    }
}
