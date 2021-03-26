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

class Section
{
    /**
     * Section
     * 
     * @param string $str
     * @param int    $starting = 0
     * @param int    $count    = NULL
     * @param string $encoding = 'utf-8'
     * 
     * @return string
     */
    public static function use(string $str, int $starting = 0, int $count = NULL, string $encoding = 'utf-8') : string
    {
        return mb_substr($str, $starting, $count, $encoding);
    }
}
