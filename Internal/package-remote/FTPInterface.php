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

interface FTPInterface
{
    /**
     * Change Folder
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function changeFolder(String $path) : Bool;

    /**
     * Get Files
     * 
     * @param string $path
     * @param string $extension = NULL
     * 
     * @return array
     */
    public function files(String $path, String $extension = NULL) : Array;

    /**
     * Get File Size
     * 
     * @param string $path
     * @param string $type = 'b' - options[b|kb|mb|gb]
     * 
     * @return float
     */
    public function fileSize(String $path, String $type = 'b', Int $decimal = 2) : Float;
}
