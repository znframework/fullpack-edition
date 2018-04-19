<?php namespace ZN\Remote;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface RemoteInterface
{
    /**
     * Different Connection
     * 
     * @param array $config
     * 
     * @return Connection
     */
    public function differentConnection(Array $config);

    /**
     * Create Folder
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function createFolder(String $path) : Bool;

    /**
     * Delete Folder
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function deleteFolder(String $path) : Bool;

    /**
     * Rename
     * 
     * @param string $oldName
     * @param string $newName
     * 
     * @return bool
     */
    public function rename(String $oldName, String $newName) : Bool;

    /**
     * Delete File
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function deleteFile(String $path) : Bool;

     /**
     * Permission
     * 
     * @param string $path
     * @param int    $type = 0755
     * 
     * @return bool
     */
    public function permission(String $path, Int $type = 0755) : Bool;

    /**
     * File Upload
     * 
     * @param string $localPath
     * @param string $remotePath
     * 
     * @return bool
     */
    public function upload(String $localPath, String $remotePath) : Bool;

    /**
     * File Download
     * 
     * @param string $remotePath
     * @param string $localPath
     * 
     * @return bool
     */
    public function download(String $remotePath, String $localPath) : Bool;
}
