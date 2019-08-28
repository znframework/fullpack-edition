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

class ColorConverter
{
    public static function run($rgb)
    {
        // Renkler küçük isimlerle yazılmıştır.
        $rgb    = strtolower($rgb);
        $colors = Properties::$colors;

        if( isset($colors[$rgb]) )
        {
            return $colors[$rgb];
        }
        else
        {
            return $rgb ?? '0|0|0|127';
        }
    }
}
