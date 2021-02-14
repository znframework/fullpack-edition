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

class ThumbCleaner
{   
    /**
     * Clean thumb files
     * 
     * @param string $ile
     * @param bool   $origin = false
     * @param string $path   = NULL
     */
    public static function clean(String $file, Bool $origin = false, String $path = NULL)
    {
        if( is_file($file) )
        {
            $dir      = pathinfo($file, PATHINFO_DIRNAME);
            $filename = pathinfo($file, PATHINFO_FILENAME);
            
            if( is_dir($directory = $dir . '/' . $path . '/') )
            {
                if( $files = preg_grep('/^' . preg_quote($filename) . '/', Filesystem::getFiles($directory)) )
                {
                    foreach( $files as $thumbFile )
                    {
                        unlink($directory . $thumbFile);
                    }			
                }				
            }

            if( $origin === true )
            {
                unlink($file); // @codeCoverageIgnore
            }         
        }
    }
}   
