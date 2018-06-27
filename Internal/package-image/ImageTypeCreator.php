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

use ZN\Filesystem;

class ImageTypeCreator
{
    /**
     * Image type creator
     * 
     * @param resources $files
     * @param string    $path 
     * @param int       $quality = 0
     * 
     * @return bool
     */
    public static function create($files, $path, $quality = 0)
    {
        switch( self::getFileExtension($path) )
        {
            case 'png' : 
                if( $quality > 10 )
                {
                    $quality = (int) ($quality / 10);
                }
                return imagepng($files, $path, $quality ?: 8 );
            case 'gif' : return imagegif($files, $path);
            case 'jpg' :
            case 'jpeg': 
            default    : return imagejpeg($files, $path, $quality ?: 80);
        }
    }

    /**
     * Image create from
     */
    public static function from($path)
    {
        switch( self::getFileExtension($path) )
        {
            case 'png' : return imagecreatefrompng($path);
            case 'gif' : return imagecreatefromgif($path);
            case 'jpg' :
            case 'jpeg':
            default    : return imagecreatefromjpeg($path);
        }
    }

    /**
     * Protected get file extension
     */
    protected static function getFileExtension($path)
    {
        return Filesystem::getExtension($path);
    }
}
