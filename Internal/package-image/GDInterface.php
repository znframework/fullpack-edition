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

interface GDInterface
{
    /**
     * Get info
     * 
     * @return array
     */
    public function info() : array;

    /**
     * Sets canvas
     * 
     * @param mixed $width
     * @param mixed $height = NULL
     * @param mixed $rgb    = 'transparent'
     * @param mixed $real   = false
     * 
     * @return GD
     */
    public function canvas($width, $height, $rgb, $real) : GD;

    /**
     * Creates form
     * 
     * @param string $source
     * 
     * @return resource
     */
    public function createFrom(string $source);

    /**
     * Set size
     * 
     * @param string $fileName
     * 
     * @return object
     */
    public function size(string $fileName) : \stdClass;

    /**
     * Get file extension
     * 
     * @param string $type = 'jpeg'
     * @param bool   $dot  = true
     * 
     * @return string
     */
    public function extension(string $type = 'jpeg', bool $dot = true) : string;

    /**
     * Get mime type
     * 
     * @param string $type = 'jpeg'
     * 
     * @return string
     */
    public function mime(string $type = 'jpeg') : string;

    /**
     * Sets alpha blending
     * 
     * @param bool $blendMode = NULL
     * 
     * @return GD
     */
    public function alphaBlending(bool $blendMode = NULL) : GD;

    /**
     * Sets save alpha
     * 
     * @param bool $save = true
     * 
     * @return GD
     */
    public function saveAlpha(bool $save = true) : GD;

    /**
     * Sets smooth
     * 
     * @param bool $mode = true
     * 
     * @return GD
     */
    public function smooth(bool $mode = true) : GD;

    /**
     * Creates Arc
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function arc(array $settings = []) : GD;

    /**
     * Creates Ellipse
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function ellipse(array $settings = []) : GD;

    /**
     * Creates Polygon
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function polygon(array $settings = []) : GD;

    /**
     * Creates Rectangle
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function rectangle(array $settings = []) : GD;

    /**
     * Fill
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function fill(array $settings = []) : GD;

    /**
     * Filter
     * 
     * @param string $filter
     * @param int    $arg1 = NULL
     * @param int    $arg2 = NULL
     * @param int    $arg3 = NULL
     * @param int    $arg4 = NULL
     * 
     * @return GD
     */
    public function filter(string $filter, int $arg1 = NULL, int $arg2 = NULL, int $arg3 = NULL, int $arg4 = NULL) : GD;

    /**
     * Flip
     * 
     * @param string $type = 'both'
     * 
     * @return GD
     */
    public function flip(string $type) : GD;

    /**
     * Creates char
     * 
     * @param string $text
     * @param array  $settings = []
     * 
     * @return GD
     */
    public function char(string $char, array $settings = []) : GD;

    /**
     * Creates text
     * 
     * @param string $text
     * @param array  $settings = []
     * 
     * @return GD
     */
    public function text(string $text, array $settings = []) : GD;

    /**
     * Set convolution
     * 
     * @param array $matrix
     * @param float $div    = 0
     * @param float $offset = 0
     * 
     * @return GD
     */
    public function convolution(array $matrix, Float $div = 0, Float $offset = 0) : GD;

    /**
     * Set interlace
     * 
     * @param int $interlace = 0
     * 
     * @return GD
     */
    public function interlace(int $interlace = 0) : GD;

    /**
     * Copy
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function copy($source, array $settings = []) : GD;

    /**
     * Mix
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function mix($source, array $settings = []) : GD;

    /**
     * Mixgray
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function mixGray($source, array $settings = []) : GD;

    /**
     * Resize / Resample
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function resample($source, array $settings = []) : GD;

    /**
     * Resize
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function resize($source, array $settings = []) : GD;

    /**
     * Crop
     * 
     * @param array $settings = []
     * 
     * @return GD
     */
    public function crop(array $settings = []) : GD;

    /**
     * Auto crop
     * 
     * @param string $mode      = 'default'
     * @param int    $threshold = .5
     * @param int    $color     = -1
     * 
     * @return GD 
     */
    public function autoCrop(string $mode = 'default', $threshold = .5, $color = -1) : GD;

    /**
     * Creates a line
     * 
     * @param array $settings = []
     * 
     * @return GD
     */
    public function line(array $settings = []) : GD;

    /**
     * Get screenshot
     * 
     * @return GD
     */
    public function screenshot() : GD;

    /**
     * Set rotate
     * 
     * @param float  $angle
     * @param string $spaceColor        = '0|0|0'
     * @param int    $ignoreTransparent = 0
     * 
     * @return GD
     */
    public function rotate(Float $angle, string $spaceColor = '0|0|0', int $ignoreTransparent = 0) : GD;

    /**
     * Set scale
     * 
     * @param int    $width
     * @param int    $height = -1
     * @param string $method = 'bilinearFixed'
     * 
     * @return GD
     */
    public function scale(int $width, int $height = -1, string $mode = 'bilinear_fixed') : GD;

    /**
     * Set interpolation
     * 
     * @param string $method = 'bilinearFixed'
     * 
     * @return GD
     */
    public function interpolation(string $method = 'bilinear_fixed') : GD;

    /**
     * Set pixed
     * 
     * @param array $settings = []
     * 
     * @return GD
     */
    public function pixel(array $settings = []) : GD;

    /**
     * Set thickness
     * 
     * @param int $thickness = 1
     * 
     * @return GD
     */
    public function thickness(int $thickness = 1) : GD;

    /**
     * Set layer effect
     * 
     * @param string $effect = 'normal'
     * 
     * @return GD
     */
    public function layerEffect(string $effect = 'normal') : GD;

    /**
     * Generate Image
     * 
     * @param string $type = NULL
     * @param string $save = NULL
     * 
     * @return resource
     */
    public function generate(string $type, string $save);
}
