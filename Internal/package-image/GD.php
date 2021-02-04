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
use ZN\Base;
use ZN\Helper;
use ZN\Singleton;
use ZN\Ability\Revolving;
use ZN\Image\Exception\InvalidArgumentException;
use ZN\Image\Exception\InvalidImageFileException;

class GD implements GDInterface
{
    use Revolving, CallableFilterMethod;

    /**
     * Call callable method
     * 
     * @const string
     */
    const call = 'callable';

    /**
     * Keeps canvas settings
     * 
     * @var resource
     */
    protected $canvas;

    /**
     * Output status
     * 
     * @var bool
     */
    protected $output = true;

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        $this->mime = Singleton::class('ZN\Helpers\Mime');
    }

    /**
     * Get info
     * 
     * @return array
     */
    public function info() : Array
    {
        return gd_info();
    }

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
    public function canvas($width, $height = NULL, $rgb = 'transparent', $real = false) : GD
    {   
        if( ! is_numeric($width) )
        {
            # width -> image
            if( $this->mime->type($width, 0) === 'image' )
            {
                # width  -> image
                # height -> width
                # rgb    -> height
                $this->createImageCanvas($width, $height, $rgb);
            }
            else
            {
                throw new InvalidImageFileException(NULL, $width);
            }
        }
        else
        {
            $this->createEmptyCanvas($width, $height, $rgb, $real);
        }
        
        $this->defaultRevolvingVariables();

        return $this;
    }

    /**
     * Creates form
     * 
     * @param string $source
     * 
     * @return resource
     */
    public function createFrom(String $source)
    {
        $type     = $this->mime->type($source, 1);
        $function = 'imagecreatefrom' . ($type ?? 'jpeg');

        if( ! function_exists($function) )
        {
            throw new InvalidImageFileException(NULL, $source);
        }

        return $function($source);
    }

    /**
     * Set size
     * 
     * @param string $fileName
     * 
     * @return object
     */
    public function size(String $fileName) : stdClass
    {
        $data = [];

        if( in_array(strlen(pathinfo($fileName, PATHINFO_EXTENSION)), [3, 4]) && $this->mime->type($fileName, 0) === 'image' )
        {
            $data = getimagesize($fileName);
        }
        else if( is_string($fileName) )
        {
            $data = getimagesizefromstring($fileName);
        }
        
        if( empty($data) )
        {
            throw new InvalidArgumentException(NULL, '[file]');
        }

        return (object)
        [
            'width'     => $data[0],
            'height'    => $data[1],
            'extension' => $this->extension($data[2]),
            'img'       => $data[3],
            'bits'      => $data['bits'],
            'mime'      => $data['mime']
        ];
    }

    /**
     * Get file extension
     * 
     * @param string $type = 'jpeg'
     * @param bool   $dot  = true
     * 
     * @return string
     */
    public function extension(String $type = 'jpeg', Bool $dot = true) : String
    {
        return image_type_to_extension(Helper::toConstant($type, 'IMAGETYPE_'), $dot);
    }

    /**
     * Get mime type
     * 
     * @param string $type = 'jpeg'
     * 
     * @return string
     */
    public function mime(String $type = 'jpeg') : String
    {
        return image_type_to_mime_type(Helper::toConstant($type, 'IMAGETYPE_'));
    }

    /**
     * Sets alpha blending
     * 
     * @param bool $blendMode = NULL
     * 
     * @return GD
     */
    public function alphaBlending(Bool $blendMode = NULL) : GD
    {
        imagealphablending($this->canvas, (bool) $blendMode);

        return $this;
    }

    /**
     * Sets save alpha
     * 
     * @param bool $save = true
     * 
     * @return GD
     */
    public function saveAlpha(Bool $save = true) : GD
    {
        imagesavealpha($this->canvas, $save);

        return $this;
    }

    /**
     * Sets smooth
     * 
     * @param bool $mode = true
     * 
     * @return GD
     */
    public function smooth(Bool $mode = true) : GD
    {
        imageantialias($this->canvas, $mode);

        return $this;
    }

    /**
     * Creates Arc
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function arc(Array $settings = []) : GD
    {
        $x      = $settings['x']       ?? $this->x      ?? 0;
        $y      = $settings['y']       ?? $this->y      ?? 0;
        $width  = $settings['width']   ?? $this->width  ?? 100;
        $height = $settings['height']  ?? $this->height ?? 100;
        $start  = $settings['start']   ?? $this->start  ?? 0;
        $end    = $settings['end']     ?? $this->end    ?? 360;
        $color  = $settings['color']   ?? $this->color  ?? '0|0|0';
        $style  = $settings['type']    ?? $this->type   ?? NULL;

        if( $style === NULL )
        {
            imagearc($this->canvas, $x, $y, $width, $height, $start, $end, $this->allocate($color));
        }
        else
        {
            imagefilledarc
            (
                $this->canvas, $x, $y, $width, $height, $start, $end,
                $this->allocate($color),
                Helper::toConstant($style, 'IMG_ARC_')
            );
        }
        
        $this->defaultRevolvingVariables();

        return $this;
    }

    /**
     * Creates Ellipse
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function ellipse(Array $settings = []) : GD
    {
        $x      = $settings['x']       ?? $this->x      ?? 0;
        $y      = $settings['y']       ?? $this->y      ?? 0;
        $width  = $settings['width']   ?? $this->width  ?? 100;
        $height = $settings['height']  ?? $this->height ?? 100;
        $color  = $settings['color']   ?? $this->color  ?? '0|0|0';
        $style  = $settings['type']    ?? $this->type   ?? NULL;

        if( $style === NULL )
        {
            imageellipse($this->canvas, $x, $y, $width, $height, $this->allocate($color));
        }
        else
        {
            imagefilledellipse($this->canvas, $x, $y, $width, $height, $this->allocate($color));
        }

        $this->defaultRevolvingVariables();

        return $this;
    }

    /**
     * Creates Polygon
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function polygon(Array $settings = []) : GD
    {
        $points     = $settings['points']     ?? $this->points     ?? 0;
        $pointCount = $settings['pointCount'] ?? $this->pointCount ?? (ceil(count($this->points ?? $points) / 2));
        $color      = $settings['color']      ?? $this->color      ?? '0|0|0';
        $style      = $settings['type']       ?? $this->type       ?? NULL;

        if( $style === NULL )
        {
            imagepolygon($this->canvas, $points, $pointCount, $this->allocate($color));
        }
        else
        {
            imagefilledpolygon($this->canvas, $points, $pointCount, $this->allocate($color));
        }

        $this->defaultRevolvingVariables();

        return $this;
    }

    /**
     * Creates Rectangle
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function rectangle(Array $settings = []) : GD
    {
        $x      = $settings['x']      ?? $this->x      ?? 0;
        $y      = $settings['y']      ?? $this->y      ?? 0;
        $width  = $settings['width']  ?? $this->width  ?? 100;
        $height = $settings['height'] ?? $this->height ?? 100;
        $color  = $settings['color']  ?? $this->color  ?? '0|0|0';
        $style  = $settings['type']   ?? $this->type   ?? NULL;

        $width  += $x;
        $height += $y;

        if( $style === NULL )
        {
            imagerectangle($this->canvas, $x, $y, $width, $height, $this->allocate($color));
        }
        else
        {
            imagefilledrectangle($this->canvas, $x, $y, $width, $height, $this->allocate($color));
        }

        $this->defaultRevolvingVariables();

        return $this;
    }


    /**
     * Fill
     * 
     * @param array $settings []
     * 
     * @return GD
     */
    public function fill(Array $settings = []) : GD
    {
        $x           = $settings['x']           ?? $this->x           ?? 0;
        $y           = $settings['y']           ?? $this->y           ?? 0;
        $color       = $settings['color']       ?? $this->color       ?? '0|0|0';
        $borderColor = $settings['borderColor'] ?? $this->borderColor ?? NULL;
        
        if( $borderColor === NULL )
        {
            imagefill($this->canvas, $x, $y, $this->allocate($color));
        }
        else
        {
            imagefilltoborder($this->canvas, $x, $y, $this->allocate($borderColor), $this->allocate($color));
        }

        $this->defaultRevolvingVariables();

        return $this;
    }

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
    public function filter(String $filter, Int $arg1 = NULL, Int $arg2 = NULL, Int $arg3 = NULL, Int $arg4 = NULL) : GD
    {
        $filters = Singleton::class('ZN\DataTypes\Collection')->data(func_get_args())
                                                              ->removeFirst()
                                                              ->deleteElement(NULL)
                                                              ->get();
        
        imagefilter($this->canvas, Helper::toConstant($filter, 'IMG_FILTER_'), ...$filters);

        return $this;
    }

    /**
     * Flip
     * 
     * @param string $type = 'both'
     * 
     * @return GD
     */
    public function flip(String $type = 'both') : GD
    {
        imageflip($this->canvas, Helper::toConstant($type, 'IMG_FLIP_'));

        return $this;
    }

    /**
     * Creates char
     * 
     * @param string $text
     * @param array  $settings = []
     * 
     * @return GD
     */
    public function char(String $char, Array $settings = [], $function = 'char') : GD
    {
        $x      = $settings['x']     ?? $this->x     ?? 0;
        $y      = $settings['y']     ?? $this->y     ??  0;
        $font   = $settings['font']  ?? $this->font  ??  1;
        $color  = $settings['color'] ?? $this->color ??  '0|0|0';
        $type   = $settings['type']  ?? $this->type  ??  NULL;
        $method = 'image' . $function;
        
        if( $type === 'vertical')
        {
            $method .= 'up';

            $method($this->canvas, $font, $x, $y, $char, $this->allocate($color));
        }
        else
        {
            $method($this->canvas, $font, $x, $y, $char, $this->allocate($color));
        }

        $this->defaultRevolvingVariables();

        return $this;
    }

    /**
     * Creates text
     * 
     * @param string $text
     * @param array  $settings = []
     * 
     * @return GD
     */
    public function text(String $text, Array $settings = []) : GD
    {
        return $this->char($text, $settings, 'string');
    }

    /**
     * Set convolution
     * 
     * @param array $matrix
     * @param float $div    = 0
     * @param float $offset = 0
     * 
     * @return GD
     */
    public function convolution(Array $matrix, Float $div = 0, Float $offset = 0) : GD
    {
        imageconvolution($this->canvas, $matrix, $div, $offset);

        return $this;
    }

    /**
     * Set interlace
     * 
     * @param int $interlace = 0
     * 
     * @return GD
     */
    public function interlace(Int $interlace = 0) : GD
    {
        imageinterlace($this->canvas, $interlace);

        return $this;
    }

    /**
     * Copy
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function copy($source, Array $settings = []) : GD
    {
        if( is_file($file = $source) )
        {
            $source = $this->createFrom($source);
        }

        if( ! Base::isResourceObject($source) )
        {
            throw new InvalidArgumentException(NULL, '[resource]');
        }

        $this->alignImageWatermark($file);

        $xt     = $settings['xt']     ?? $this->target[0] ?? 0;
        $yt     = $settings['yt']     ?? $this->target[1] ?? 0;
        $xs     = $settings['xs']     ?? $this->source[0] ?? 0;
        $ys     = $settings['ys']     ?? $this->source[1] ?? 0;
        $width  = $settings['width']  ?? $this->width     ?? 0;
        $height = $settings['height'] ?? $this->height    ?? 0;

        imagecopy($this->canvas, $source, $xt, $yt, $xs, $ys, $width, $height);
        
        $this->defaultRevolvingVariables();

        return $this;
    }

    /**
     * Mix
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function mix($source, Array $settings = [], $function = 'imagecopymerge') : GD
    {
        if( is_file($file = $source) )
        {
            $source = $this->createFrom($source);
        }

        if( ! Base::isResourceObject($source) )
        {
            throw new InvalidArgumentException(NULL, '[resource]');
        }
        
        $this->alignImageWatermark($file);

        $xt      = $settings['xt']      ?? $this->target[0] ?? 0;
        $yt      = $settings['yt']      ?? $this->target[1] ?? 0;
        $xs      = $settings['xs']      ?? $this->source[0] ?? 0;
        $ys      = $settings['ys']      ?? $this->source[1] ?? 0;
        $width   = $settings['width']   ?? $this->width     ?? 0;
        $height  = $settings['height']  ?? $this->height    ?? 0;
        $percent = $settings['percent'] ?? $this->percent   ?? 100;

        $function($this->canvas, $source, $xt, $yt, $xs, $ys, $width, $height, $percent);

        $this->defaultRevolvingVariables();

        return $this;
    }

    /**
     * Mixgray
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function mixGray($source, Array $settings = []) : GD
    {
        $this->mix($source, $settings, 'imagecopymergegray');

        return $this;
    }

    /**
     * Resize / Resample
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function resample($source, Array $settings = [], $function = 'imagecopyresampled') : GD
    {
        if( is_file($file = $source) )
        {
            $source = $this->createFrom($source);
        }

        if( ! Base::isResourceObject($source) )
        {
            throw new InvalidArgumentException(NULL, '[resource]');
        }

        $this->alignImageWatermark($file);

        $xt = $settings['xt'] ?? $this->target[0]    ?? 0;
        $yt = $settings['yt'] ?? $this->target[1]    ?? 0;
        $xs = $settings['xs'] ?? $this->source[0]    ?? 0;
        $ys = $settings['ys'] ?? $this->source[1]    ?? 0;
        $wt = $settings['wt'] ?? $this->width        ?? $this->targetWidth  ?? 0;
        $ht = $settings['ht'] ?? $this->height       ?? $this->targetHeight ?? 0;
        $ws = $settings['ws'] ?? $this->sourceWidth  ?? 0;
        $hs = $settings['hs'] ?? $this->sourceHeight ?? 0;

        $function($this->canvas, $source, $xt, $yt, $xs, $ys, $wt, $ht, $ws, $hs);

        $this->defaultRevolvingVariables();

        return $this;
    }

    /**
     * Resize
     * 
     * @param string|resource $source
     * @param array           $settings = []
     * 
     * @return GD
     */
    public function resize($source, Array $settings = []) : GD
    {
        $this->resample($source, $settings, 'imagecopyresized');

        return $this;
    }

    /**
     * Crop
     * 
     * @param array $settings = []
     * 
     * @return GD
     */
    public function crop(Array $settings = []) : GD
    {
        $sets = 
        [
            'x'      => $settings['x']      ?? $this->x      ?? 0,
            'y'      => $settings['y']      ?? $this->y      ?? 0,
            'width'  => $settings['width']  ?? $this->width  ?? 100,
            'height' => $settings['height'] ?? $this->height ?? 0,

        ];

        $this->canvas = imagecrop($this->canvas, $sets);

        $this->defaultRevolvingVariables();
        
        return $this;
    }

    /**
     * Auto crop
     * 
     * @param string $mode      = 'default'
     * @param int    $threshold = .5
     * @param int    $color     = -1
     * 
     * @return GD 
     */
    public function autoCrop(String $mode = 'default', $threshold = .5, $color = -1) : GD
    {
        $this->canvas = imagecropauto
        (
            $this->canvas, 
            Helper::toConstant($mode, 'IMG_CROP_'), 
            $threshold, 
            $mode === 'threshold' ? $this->allocate($color) : $color
        );

        $this->defaultRevolvingVariables();

        return $this;
    }

    /**
     * Creates a line
     * 
     * @param array $settings = []
     * 
     * @return GD
     */
    public function line(Array $settings = []) : GD
    {
        $x1   = $settings['x1']    ?? $this->x1    ?? 0;
        $y1   = $settings['y1']    ?? $this->y1    ?? 0;
        $x2   = $settings['x2']    ?? $this->x2    ?? 0;
        $y2   = $settings['y2']    ?? $this->y2    ?? 0;
        $rgb  = $settings['color'] ?? $this->color ?? '0|0|0';
        $type = $settings['type']  ?? $this->type  ?? 'solid';
        
        if( $type === 'solid' )
        {
            imageline($this->canvas, $x1, $y1, $x2, $y2, $this->allocate($rgb));
        }
        elseif( $type === 'dashed' )
        {
            imagedashedline($this->canvas, $x1, $y1, $x2, $y2, $this->allocate($rgb));
        }

        $this->defaultRevolvingVariables();

        return $this;
    }

    /**
     * Get screenshot
     * 
     * @return GD
     */
    public function screenshot() : GD
    {
        $this->canvas = imagegrabscreen();
        return $this;
    }

    /**
     * Set rotate
     * 
     * @param float  $angle
     * @param string $spaceColor        = '0|0|0'
     * @param int    $ignoreTransparent = 0
     * 
     * @return GD
     */
    public function rotate(Float $angle, String $spaceColor = '0|0|0', Int $ignoreTransparent = 0) : GD
    {
        $this->canvas = imagerotate($this->canvas, $angle, $this->allocate($spaceColor), $ignoreTransparent);

        if( $spaceColor === 'transparent' )
        {
            $this->saveAlpha();
        }

        return $this;
    }

    /**
     * Set scale
     * 
     * @param int    $width
     * @param int    $height = -1
     * @param string $method = 'bilinearFixed'
     * 
     * @return GD
     */
    public function scale(Int $width, Int $height = -1, String $mode = 'bilinearFixed') : GD
    {
        $this->canvas = imagescale($this->canvas, $width, $height, Helper::toConstant($mode, 'IMG_'));

        return $this;
    }

    /**
     * Set interpolation
     * 
     * @param string $method = 'bilinearFixed'
     * 
     * @return GD
     */
    public function interpolation(String $method = 'bilinearFixed') : GD
    {
        imagesetinterpolation($this->canvas, Helper::toConstant($method, 'IMG_'));

        return $this;
    }

    /**
     * Set pixed
     * 
     * @param array $settings = []
     * 
     * @return GD
     */
    public function pixel(Array $settings = []) : GD
    {
        $x   = $settings['x']     ?? $this->x     ?? 0;
        $y   = $settings['y']     ?? $this->y     ?? 0;
        $rgb = $settings['color'] ?? $this->color ?? '0|0|0';

        imagesetpixel($this->canvas, $x, $y, $this->allocate($rgb));

        $this->defaultRevolvingVariables();

        return $this;
    }

    /**
     * Set thickness
     * 
     * @param int $thickness = 1
     * 
     * @return GD
     */
    public function thickness(Int $thickness = 1) : GD
    {
        imagesetthickness($this->canvas, $thickness);

        return $this;
    }

    /**
     * Set layer effect
     * 
     * @param string $effect = 'normal'
     * 
     * @return GD
     */
    public function layerEffect(String $effect = 'normal') : GD
    {
        imagelayereffect($this->canvas, Helper::toConstant($effect, 'IMG_EFFECT_'));

        return $this;
    }

    /**
     * Generate Image
     * 
     * @param string $type = NULL
     * @param string $save = NULL
     * 
     * @return resource
     */
    public function generate(String $type = NULL, String $save = NULL)
    {
        $canvas = $this->canvas;
        
        if( ! empty($type) )
        {
            $this->type = $type;
        }

        if( ! empty($save) )
        {
            $this->save = $save;
        }

        if( empty($this->save) && $this->output === true )
        {
            $this->getImageContent();
        }

        $this->generateImageType();
        $this->destroyImage();
        $this->defaultVariables();

        return $canvas;
    }

    /**
     * Protected create image canvas
     */
    protected function createImageCanvas($image, $width, $height)
    {
        $this->type = $this->mime->type($image, 1);
            
        $this->imageSize = ! isset($width) ? getimagesize($image) : [(int) $width, (int) $height];

        $this->canvas = $this->createFrom($image);
    }

    /**
     * Protected create empty canvas
     */
    protected function createEmptyCanvas($width, $height, $rgb, $real)
    {
        $this->imageSize = [$width, $height];

        if( $real === false )
        {
            $this->canvas = imagecreate($width, $height);
        }
        else
        {
            $this->canvas = imagecreatetruecolor($width, $height);
        }

        if( ! empty($rgb) )
        {
            $this->allocate($rgb);
        }
    }
    
    /**
     * Protected align image watermark
     */
    protected function alignImageWatermark($source)
    {
        if( is_string($this->target ?? NULL) )
        {
            $size = getimagesize($source);

            $this->width  = $this->width  ?? $size[0];
            $this->height = $this->height ?? $size[1];

            $return = WatermarkImageAligner::align($this->target, $this->width, $this->height, $this->imageSize[0], $this->imageSize[1], $this->margin ?? 0);

            $this->target = $return;
            
            if( isset($this->x) ) $this->source[0] = $this->x;
            if( isset($this->y) ) $this->source[1] = $this->y;
        }
    }

    /**
     * Protected Allocate
     */
    protected function allocate($rgb)
    {
        $rgb = explode('|', ColorConverter::run($rgb));

        $red   = $rgb[0] ?? 0;
        $green = $rgb[1] ?? 0;
        $blue  = $rgb[2] ?? 0;
        $alpha = $rgb[3] ?? 0;

        return imagecolorallocatealpha($this->canvas, $red, $green, $blue, $alpha);
    }

    /**
     * Protected Destroy
     */
    protected function destroyImage()
    {
        imagedestroy($this->canvas);

        return $this;
    }

    /**
     * Protected Content
     */
    protected function getImageContent()
    {
        header("Content-type: image/".($this->type ?? 'jpeg'));
    }

    /**
     * Protected Default Variables
     */
    protected function defaultVariables()
    {
        $this->canvas  = NULL;
        $this->save    = NULL;
        $this->output  = true;
        $this->quality = NULL;
        $this->type    = NULL;
    }

    /**
     * Protected Types
     */
    protected function generateImageType()
    {
        $type = strtolower($this->type ?? 'jpeg');

        if( ! empty($this->save) )
        {
            $save = Base::suffix($this->save, '.'.($type === 'jpeg' ? 'jpg' : $type));
        }
        else
        {
            $save = NULL;
        }
        
        $function = 'image' . $type;

        $function($this->canvas, $save, $this->quality ?? ($type === 'png' ? 8 : 80));

        return $this;
    }
}
