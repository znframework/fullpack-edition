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

class Substitution
{
    /**
     * Reshuffle
     * 
     * @param string $str
     * @param string $shuffle
     * @param string $reshuffle
     * 
     * @return string
     */
    public static function reshuffle(string $str, string $shuffle, string $reshuffle) : string
    {
        $shuffleEx = explode($shuffle, $str);

        $newstr = '';

        foreach( $shuffleEx as $v )
        {
            $newstr .=  str_replace($reshuffle, $shuffle, $v).$reshuffle;
        }

        return substr($newstr, 0, -strlen($reshuffle));
    }

    /**
     * Placement
     * 
     * @param string $str
     * @param string $delimiter
     * @param array  $array
     * 
     * @return string
     */
    public static function placement(string $str, string $delimiter, array $array) : string
    {
        if( ! empty($delimiter) )
        {
            $strex = explode($delimiter, $str);
        }
        else
        {
            return $str;
        }

        if( (count($strex) - 1) !== count($array) )
        {
            return $str;
        }

        $newstr = '';

        for( $i = 0; $i < count($array); $i++ )
        {
            $newstr .= $strex[$i].$array[$i];
        }

        return $newstr.$strex[count($array)];
    }

    /**
     * Replace
     * 
     * @param string $string
     * @param mixed  $oldChar
     * @param mixed  $newChar
     * @param bool   $case = true
     * 
     * @return string
     */
    public static function replace(string $string, $oldChar, $newChar = NULL, bool $case = true) : string
    {
        if( $case === true )
        {
            $function = 'str_replace';
        }
        else
        {
            $function = 'str_ireplace';
        }

        return $function($oldChar, $newChar, $string);
    }
}
