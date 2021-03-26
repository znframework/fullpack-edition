<?php namespace ZN\Filesystem;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Datatype;

class Download
{
    /**
     * Download
     * 
     * @param string $file
     */
    public static function start(string $file)
    {
        if( ! Info::available($file) )
        {
            throw new Exception\FileNotFoundException($file);
        }

        self::readFileContent($file);
    }

    /**
     * Protected read file content
     */
    protected static function readFileContent($file)
    {
        header("Content-type: application/x-download");
        header("Content-Disposition: attachment; filename=" . Datatype::divide($file, '/', -1));

        readfile($file);
    }
}
