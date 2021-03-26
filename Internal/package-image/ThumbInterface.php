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

interface ThumbInterface
{
    /**
     * Sets fill background
     * 
     * @param int $x
     * @param int $y
     * @param string $color = 'white'
     * @param string $align = 'center'
     * 
     * @return Thumb
     */
    public function background(int $x, int $y, string $color = 'white', string $align = 'center') : Thumb;

    /**
     * Watermark.
     * 
     * @return Thumb
     */
    public function watermark(string $source, string $align = NULL, $margin = 0) : Thumb;

    /**
     * Refresh image filtering.
     * 
     * @return Thumb
     */
    public function refresh() : Thumb;

    /**
     * Sets file path
     * 
     * @param string $file
     * 
     * @return Thumb
     */
    public function path(string $file) : Thumb;

    /**
     * Sets image quality
     * 
     * @param int $quality
     * 
     * @return Thumb
     */
    public function quality(int $quality) : Thumb;

    /**
     * Crop image
     * 
     * @param int $x
     * @param int $y
     * 
     * @return Thumb
     */
    public function crop(int $x, int $y) : Thumb;

    /**
     * Sets image size
     * 
     * @param int $width
     * @param int $height
     * 
     * @return Thumb
     */
    public function size(int $width, int $height) : Thumb;

    /**
     * Sets image resize
     * 
     * @param int $width
     * @param int $height
     * 
     * @return Thumb
     */
    public function resize(int $width, int $height) : Thumb;

    /**
     * Sets image proportional size
     * 
     * @param int $width
     * @param int $height
     * 
     * @return Thumb
     */
    public function prosize(int $width, int $height = 0) : Thumb;

    /**
     * Create new image
     * 
     * @param string $path = NULL
     * 
     * @return string
     */
    public function create(string $path) : string;

    /**
     * Get proportional size
     * 
     * @param int $width  = 0
     * @param int $height = 0
     * 
     * @return object|false
     */
    public function getProsize(int $width, int $height);

    /**
     * Clean thumb files
     * 
     * @param string $ile
     * @param bool   $origin = false
     */
    public function clean(string $path, bool $origin = false);
}
