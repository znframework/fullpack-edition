<?php namespace ZN\Image;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class CoordinateRateCalculator
{
    /**
     * Calculates the ratio according to the entered numerical value.
     * 
     * @param float $size
     * @param float &$c1
     * @param float &$c2
     */
    public static function run($size, &$c1, &$c2)
    {
        if( $size > 0 )
        {
            if( $size <= $c2 )
            {
                $rate = $c2 / $size; $c2 = $size; $c1 = $c1 / $rate;
            }
            else
            {
                $rate = $size / $c2; $c2 = $size; $c1 = $c1 * $rate;
            }
        }
    }
}
