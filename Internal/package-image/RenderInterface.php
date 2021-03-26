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

use stdClass;

interface RenderInterface
{
    /**
     * Clean thumb files
     * 
     * @param string $ile
     * @param bool   $origin = false
     */
    public function cleaner(string $path, bool $origin = false);
    
    /**
     * Get prosize
     * 
     * @param string $path
     * @param int    $width = 0
     * @param int    $height = 0
     * 
     * @return object
     */
    public function getProsize(string $path, int $width = 0, int $height = 0) : stdClass;

    /**
     * Thumb
     * 
     * @param string $fpath
     * @param array  $set
     * 
     * @return string
     */
    public function thumb(string $fpath, array $set) : string;
}
