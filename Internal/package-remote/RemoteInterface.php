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
     * Alias different connection
     * 
     * @param array $config
     * 
     * @return Connection
     */
    public function new(array $config);

    /**
     * Different Connection
     * 
     * @param array $config
     * 
     * @return Connection
     */
    public function differentConnection(array $config);

    /**
     * Create Folder
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function createFolder(string $path) : bool;

    /**
     * Delete Folder
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function deleteFolder(string $path) : bool;

    /**
     * Rename
     * 
     * @param string $oldName
     * @param string $newName
     * 
     * @return bool
     */
    public function rename(string $oldName, string $newName) : bool;

    /**
     * Delete File
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function deleteFile(string $path) : bool;

     /**
     * Permission
     * 
     * @param string $path
     * @param int    $type = 0755
     * 
     * @return bool
     */
    public function permission(string $path, int $type = 0755) : bool;

    /**
     * File Upload
     * 
     * @param string $localPath
     * @param string $remotePath
     * 
     * @return bool
     */
    public function upload(string $localPath, string $remotePath) : bool;

    /**
     * File Download
     * 
     * @param string $remotePath
     * @param string $localPath
     * 
     * @return bool
     */
    public function download(string $remotePath, string $localPath) : bool;
}
