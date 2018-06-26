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

class GDFilter
{
    /**
     * Applies the used filters belonging to the GD class.
     * 
     * @param string $file
     * @param array  $filters
     */
    public static function apply($file, $filters)
    {
        if( ! empty($filters) )
        { 
            $gd = self::getSingletonGDClass();

            $gd->canvas($file);
            
            foreach( $filters as $filter )
            {
                $method     = $filter[0];
                $parameters = $filter[1] ?? [];

                $gd->$method(...(array) $parameters);
            }
    
            $gd->generate(MimeTypeFinder::get($file), $file);
        }
    }

    /**
     * Protected get singleton GD class
     */
    protected static function getSingletonGDClass()
    {
        return Singleton::class('ZN\Image\GD');
    }
}
