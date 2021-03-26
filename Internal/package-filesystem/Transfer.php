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
    public static function settings(array $set = [])
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
    public static function upload(string $fileName = 'upload', string $rootDir = UPLOADS_DIR) : bool
    {
        return \Upload::start($fileName, $rootDir);
    }

    /**
     * Download
     * 
     * @param string $file
     */
    public static function download(string $file)
    {
        Download::start($file);
    }
}
