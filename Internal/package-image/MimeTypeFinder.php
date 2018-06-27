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

use ZN\Singleton;

class MimeTypeFinder
{
    /**
     * Finder mime type.
     * 
     * @param string $file
     */
    public static function get($file)
    {
        $type = str_replace('image/', NULL, Singleton::class('ZN\Helpers\Mime')->type($file));

        return $type === 'jpg' ? 'jpeg' : $type;
    }
}
