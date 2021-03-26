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

class Excluding
{
    /**
     * Exclude Element
     * 
     * @param array $array
     * @param array $excluding
     * 
     * @return array
     */
    public static function use(array $array, array $excluding) : array
    {
        $newArray = [];

        if( count($excluding) > count($array) )
        {
            return $newArray;
        }

        foreach( $array as $key => $val )
        {
            if( ! in_array($val, $excluding) && ! in_array($key, $excluding) )
            {
                $newArray[$key] = $val;
            }
        }

        return $newArray;
    }
}
