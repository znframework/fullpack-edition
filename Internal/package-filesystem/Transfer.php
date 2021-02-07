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

use ZN\Filesystem\Exception\FileNotFoundException;

class Transfer
{
    /**
     * Settings
     * 
     * @param array $set
     * 
     * @return Upload
     */
    public static function settings(Array $set = [])
    {
        return \Upload::settings($set);
    }

    /**
     * Upload
     * 
     * @param string $file = 'upload'
     * @param string $rootDir = UPLOADS_DIR
     * 
     * @return bool
     */
    public static function upload(String $fileName = 'upload', String $rootDir = UPLOADS_DIR) : Bool
    {
        return \Upload::start($fileName, $rootDir);
    }

    /**
     * Download
     * 
     * @param string $file
     */
    public static function download(String $file)
    {
        Download::start($file);
    }
}
