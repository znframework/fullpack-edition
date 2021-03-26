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

class Force
{
    /**
     * Force Values
     * 
     * @param array    $array
     * @param callable $callable
     * 
     * @return array
     */
    public static function values(array $array, callable $callable) : array
    {
        return array_map($callable, $array);
    }

    /**
     * Force Keys
     * 
     * @param array    $array
     * @param callable $callable
     * 
     * @return array
     */
    public static function keys(array $array, callable $callable) : array
    {
        $keys = array_map($callable, array_keys($array));

        return array_combine($keys, array_values($array));
    }

    /**
     * Force All
     * 
     * @param array    $array
     * @param callable $callable
     * 
     * @return array
     */
    public static function do(array $array, callable $callable) : array
    {
        $values = array_values(array_map($callable, $array));
        $keys   = array_values(array_map($callable, array_keys($array)));

        return array_combine($keys, $values);
    }
}
