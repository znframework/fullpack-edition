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
use ZN\Datatype;

class Split
{
    /**
     * Split Upper Case
     * 
     * @param string $string
     * 
     * @return array
     */
    public static function upperCase(string $string) : array
    {
        return Datatype::splitUpperCase($string);
    }

    /**
     * Apportion
     * 
     * @param string $string
     * @param int    $length = 76
     * @param string $end    = PHP_EOL
     */
    public static function apportion(string $string, int $length = 76, string $end = PHP_EOL) : string
    {
        $arrayChunk = array_chunk(preg_split("//u", $string, -1, PREG_SPLIT_NO_EMPTY), $length);

        $string = '';

        foreach( $arrayChunk as $chunk )
        {
            $string .= implode('', $chunk) . $end;
        }

        return Base::removeSuffix($string, $end);
    }

    /**
     * Divide
     * 
     * @param string $str       = NULL
     * @param string $separator = '|'
     * @param string $index     = '0'
     * @param string $count     = '1' - added[5.6.02]
     */
    public static function divide(string $str = NULL, string $separator = '|', string $index = '0', string $count = '1')
    {
        return Datatype::divide($str, $separator, $index, $count);
    }
}
