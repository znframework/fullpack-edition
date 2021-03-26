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

class GetElement
{
    /**
     * Get Last Element
     * 
     * @param array $array
     * @param int   $count       = 1
     * @param bool  $preserveKey = false
     * 
     * @return array
     */
    public static function last(array $array, int $count = 1, bool $preserveKey = false)
    {
        if( $count <= 1 )
        {
            $array = end($array) ?? NULL;
        }
        else
        {
            return array_slice($array, -$count, NULL, $preserveKey);
        }

        return $array;
    }

    /**
     * Get First Element
     * 
     * @param array $array
     * @param int   $count       = 1
     * @param bool  $preserveKey = false
     * 
     * @return array
     */
    public static function first(array $array, int $count = 1, bool $preserveKey = false)
    {
        if( $count <= 1 )
        {
            $array = current($array) ?? NULL;
        }
        else
        {
            return array_slice($array, 0, $count, $preserveKey);
        }

        return $array;
    }

    /**
     * Get values by key
     * 
     * 5.7.6[added]
     * 
     * @param array  $arrays
     * @param string $pick
     * 
     * @return array
     */
    public static function pick(array $arrays, string $pick) : array
    {
        $values = [];
    
        foreach( $arrays as $array ) 
        {
            if( is_array($array) && isset($array[$pick]) ) 
            {
                $values[] = $array[$pick];
            }
        }
    
        return $values;
    }
}
