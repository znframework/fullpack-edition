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
    public function info() : Array;

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
    public function createFrom(String $source);

    /**
     * Set size
     * 
     * @param string $fileName
     * 
     * @return object
     */
    public function size(String $fileName) : \stdClass;

    /**
     * Get file extension
     * 
     * @param string $type = 'jpeg'
     * @param bool   $dot  = true
     * 
     * @return string
     */
    public function extension(String $type = 'jpeg', Bool $dot = true) : String;

    /**
     * Get mime type
     * 
     * @param string $type = 'jpeg'
     * 
     * @return string
     */
    public function mime(String $type = 'jpeg') : String;

    /**
     * Sets alpha blending
     * 
     * @param bool $blendMode = NULL
     * 
     * @return GD
     */
    public function alphaBlending(Bool $blendMode = NULL) : GD;

    /**
     * Sets save alpha
     * 
     * @param bool $save = true
     * 
     * @return GD
     */
    public function saveAlpha(Bool $save = true) : GD;

    /**
     * Sets smooth
     * 
     * @param bool $mode = true
     * 
     * @return GD
     */
    public function smooth(Bool $mode = true) : GD;

    /**
     * Creates Arc
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function arc(Array $settings = []) : GD;

    /**
     * Creates Ellipse
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function ellipse(Array $settings = []) : GD;

    /**
     * Creates Polygon
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function polygon(Array $settings = []) : GD;

    /**
     * Creates Rectangle
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function rectangle(Array $settings = []) : GD;

    /**
     * Fill
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function fill(Array $settings = []) : GD;

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
    public function filter(String $filter, Int $arg1 = NULL, Int $arg2 = NULL, Int $arg3 = NULL, Int $arg4 = NULL) : GD;

    /**
     * Flip
     * 
     * @param string $type = 'both'
     * 
     * @return GD
     */
    public function flip(String $type) : GD;

    /**
     * Creates char
     * 
     * @param string $text
     * @param array  $settings = []
     * 
     * @return GD
     */
    public function char(String $char, Array $settings = []) : GD;

    /**
     * Creates text
     * 
     * @param string $text
     * @param array  $settings = []
     * 
     * @return GD
     */
    public function text(String $text, Array $settings = []) : GD;

    /**
     * Set convolution
     * 
     * @param array $matrix
     * @param float $div    = 0
     * @param float $offset = 0
     * 
     * @return GD
     */
    public function convolution(Array $matrix, Float $div = 0, Float $offset = 0) : GD;

    /**
     * Set interlace
     * 
     * @param int $interlace = 0
     * 
     * @return GD
     */
    public function interlace(Int $interlace = 0) : GD;

    /**
     * Copy
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function copy($source, Array $settings = []) : GD;

    /**
     * Mix
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function mix($source, Array $settings = []) : GD;

    /**
     * Mixgray
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function mixGray($source, Array $settings = []) : GD;

    /**
     * Resize / Resample
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function resample($source, Array $settings = []) : GD;

    /**
     * Resize
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function resize($source, Array $settings = []) : GD;

    /**
     * Crop
     * 
     * @param array $settings = []
     * 
     * @return GD
     */
    public function crop(Array $settings = []) : GD;

    /**
     * Auto crop
     * 
     * @param string $mode      = 'default'
     * @param int    $threshold = .5
     * @param int    $color     = -1
     * 
     * @return GD 
     */
    public function autoCrop(String $mode = 'default', $threshold = .5, $color = -1) : GD;

    /**
     * Creates a line
     * 
     * @param array $settings = []
     * 
     * @return GD
     */
    public function line(Array $settings = []) : GD;

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
    public function rotate(Float $angle, String $spaceColor = '0|0|0', Int $ignoreTransparent = 0) : GD;

    /**
     * Set scale
     * 
     * @param int    $width
     * @param int    $height = -1
     * @param string $method = 'bilinearFixed'
     * 
     * @return GD
     */
    public function scale(Int $width, Int $height = -1, String $mode = 'bilinear_fixed') : GD;

    /**
     * Set interpolation
     * 
     * @param string $method = 'bilinearFixed'
     * 
     * @return GD
     */
    public function interpolation(String $method = 'bilinear_fixed') : GD;

    /**
     * Set pixed
     * 
     * @param array $settings = []
     * 
     * @return GD
     */
    public function pixel(Array $settings = []) : GD;

    /**
     * Set thickness
     * 
     * @param int $thickness = 1
     * 
     * @return GD
     */
    public function thickness(Int $thickness = 1) : GD;

    /**
     * Set layer effect
     * 
     * @param string $effect = 'normal'
     * 
     * @return GD
     */
    public function layerEffect(String $effect = 'normal') : GD;

    /**
     * Generate Image
     * 
     * @param string $type = NULL
     * @param string $save = NULL
     * 
     * @return resource
     */
    public function generate(String $type, String $save);
}
