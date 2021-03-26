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

use ZN\DataTypes\Exception\LogicException;

class Including
{
    /**
     * Include Element
     * 
     * @param array $array
     * @param array $including
     * 
     * @return array
     */
    public static function use(array $array, array $including) : array
    {
        $newArray = [];

        if( count($including) > count($array) )
        {
            return $newArray;
        }

        foreach( $array as $key => $val )
        {
            if( in_array($val, $including) || in_array($key, $including) )
            {
                $newArray[$key] = $val;
            }
        }

        return $newArray;
    }
}
