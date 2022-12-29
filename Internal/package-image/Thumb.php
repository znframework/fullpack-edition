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

use ZN\Base;
use ZN\Request;
use ZN\Singleton;

class Thumb implements ThumbInterface
{
    use CallableFilters;

    /**
     * Keeps settings
     * 
     * @var array
     */
    protected $sets;

    /**
     * Keeps render class
     * 
     * @var object
     */
    protected $image;

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        $this->image = Singleton::class('ZN\Image\Render');
    }

    /**
     * Watermark.
     * 
     * @return Thumb
     */
    public function watermark(string $source, string $align = NULL, $margin = 0) : Thumb
    {
        $this->sets['watermark'] = [Base::removePrefix($source, Request::getBaseURL()), $align, $margin]; 

        return $this;
    }

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
    public function background(int $x, int $y, string $color = 'white', string $align = 'center') : Thumb
    {
        $this->sets['backgroundOriginX'] = $x;
        $this->sets['backgroundOriginY'] = $y;
        $this->sets['backgroundColor']   = ColorConverter::run($color);
        $this->sets['backgroundAlign']   = $align;

        return $this;
    }

    /**
     * Refresh image filtering.
     * 
     * @return Thumb
     */
    public function refresh() : Thumb
    {
        $this->sets['refresh'] = true;

        return $this;
    }

    /**
     * Sets file path
     * 
     * @param string $file
     * 
     * @return Thumb
     */
    public function path(string $file) : Thumb
    {
        $this->sets['filePath'] = $file;

        return $this;
    }

    /**
     * Sets image quality
     * 
     * @param int $quality
     * 
     * @return Thumb
     */
    public function quality(int $quality) : Thumb
    {
        $this->sets['quality'] = $quality;

        return $this;
    }

    /**
     * Crop image
     * 
     * @param int $x
     * @param int $y
     * 
     * @return Thumb
     */
    public function crop(int $x, int $y) : Thumb
    {
        $this->sets['x'] = $x;
        $this->sets['y'] = $y;

        return $this;
    }

    /**
     * Sets image size
     * 
     * @param int $width
     * @param int $height
     * 
     * @return Thumb
     */
    public function size(int $width, int $height) : Thumb
    {
        $this->sets['width']  = $width;
        $this->sets['height'] = $height;

        return $this;
    }

    /**
     * Sets image resize
     * 
     * @param int $width
     * @param int $height
     * 
     * @return Thumb
     */
    public function resize(int $width, int $height) : Thumb
    {
        $this->sets['rewidth']  = $width;
        $this->sets['reheight'] = $height;

        return $this;
    }

    /**
     * Sets image proportional size
     * 
     * @param int $width
     * @param int $height
     * 
     * @return Thumb
     */
    public function prosize(int $width, int $height = 0) : Thumb
    {
        $this->sets['prowidth']  = $width;
        $this->sets['proheight'] = $height;

        return $this;
    }

    /**
     * Create new image
     * 
     * @param string $path = NULL
     * 
     * @return string
     */
    public function create(string $path = NULL) : string
    {
        if( isset($this->sets['filePath']) )
        {
            $path = $this->sets['filePath'];
        }

        # It keeps the used filters belonging to the GD class.
        # [5.7.8]added
        $this->sets['filters'] = $this->filters;

        $settings = $this->sets;
        
        $this->sets = [];

        return $this->image->thumb($path, $settings);
    }

    /**
     * Get proportional size
     * 
     * @param int $width  = 0
     * @param int $height = 0
     * 
     * @return object|false
     */
    public function getProsize(int $width = 0, int $height = 0)
    {
        if( ! isset($this->sets['filePath']) )
        {
            return false; // @codeCoverageIgnore
        }

        return $this->image->getProsize($this->sets['filePath'], $width, $height);
    }

    /**
     * Clean thumb files
     * 
     * @param string $ile
     * @param bool   $origin = false
     */
    public function clean(string $path, bool $origin = false)
    {
        $this->image->cleaner($path, $origin);
    }
}
