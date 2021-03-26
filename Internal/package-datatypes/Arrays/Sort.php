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

use ZN\Helper;

class Sort
{
    /**
     * Array Order
     * 
     * @param array  $array
     * @param string $type  = NULL      - options[desc|asc|asckey|desckey|insens|natural|reverse|userassoc|userkey|user|random]
     * @param string $flags = 'regular' - options[regular|numeric|string|localeString]
     * 
     * @return array
     */
    public static function order(array $array, $type = NULL, $flags = 'regular') : array
    {
        $flags = Helper::toConstant($flags, 'SORT_');

        switch($type)
        {
            case 'desc'         : arsort($array, $flags);   break;
            case 'asc'          : asort($array, $flags);    break;
            case 'asckey'       : ksort($array, $flags);    break;
            case 'desckey'      : krsort($array, $flags);   break;
            case 'insens'       : natcasesort($array);      break;
            case 'natural'      : natsort($array);          break;
            case 'reverse'      : rsort($array, $flags);    break;
            case 'random'       : shuffle($array);          break;
            default             : sort($array, $flags);
        }

        return $array;
    }

    /**
     * Normal Order
     * 
     * @param array  $array
     * @param string $flags = 'regular' - options[regular|numeric|string|localeString]
     * 
     * @return array
     */
    public static function normal(array $array, string $flag = 'regular') : array
    {
        return self::order($array, 'sort', $flag);
    }

    /**
     * Descending Order
     * 
     * @param array  $array
     * @param string $flags = 'regular' - options[regular|numeric|string|localeString]
     * 
     * @return array
     */
    public static function descending(array $array, string $flag = 'regular') : array
    {
        return self::order($array, 'desc', $flag);
    }

    /**
     * Ascending Order
     * 
     * @param array  $array
     * @param string $flags = 'regular' - options[regular|numeric|string|localeString]
     * 
     * @return array
     */
    public static function ascending(array $array, string $flag = 'regular') : array
    {
        return self::order($array, 'asc', $flag);
    }

    /**
     * Ascending Key Order
     * 
     * @param array  $array
     * @param string $flags = 'regular' - options[regular|numeric|string|localeString]
     * 
     * @return array
     */
    public static function ascendingKey(array $array, string $flag = 'regular') : array
    {
        return self::order($array, 'asckey', $flag);
    }

    /**
     * Descending Key Order
     * 
     * @param array  $array
     * @param string $flags = 'regular' - options[regular|numeric|string|localeString]
     * 
     * @return array
     */
    public static function descendingKey(array $array, string $flag = 'regular') : array
    {
        return self::order($array, 'desckey', $flag);
    }

    /**
     * Reverse
     * 
     * @param array  $array
     * @param string $flags = 'regular' - options[regular|numeric|string|localeString]
     * 
     * @return array
     */
    public static function reverse(array $array, string $flag = 'regular') : array
    {
        return self::order($array, 'reverse', $flag);
    }

    /**
     * Insensitive Order
     * 
     * @param array  $array
     * @param string $flags = 'regular' - options[regular|numeric|string|localeString]
     * 
     * @return array
     */
    public static function insensitive(array $array) : array
    {
        return self::order($array, 'insens');
    }

    /**
     * Natural Order
     * 
     * @param array  $array
     * 
     * @return array
     */
    public static function natural(array $array) : array
    {
        return self::order($array, 'natural');
    }

    /**
     * Shuffle Order
     * 
     * @param array  $array
     * 
     * @return array
     */
    public static function shuffle(array $array) : array
    {
        return self::order($array, 'random');
    }
}
