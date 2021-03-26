<?php namespace ZN\Captcha;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface RenderInterface
{
    /**
     * The image specifies the path to save.
     * 
     * @param string $path
     * 
     * @return Captcha
     */
    public function path(string $path) : Render;

    /**
     * Adjust the size of the captcha.
     * 
     * @param int $width
     * @param int $height
     * 
     * @return Captcha
     */
    public function size(int $width, int $height) : Render;

    /**
     * Sets the character type.
     * 
     * @param string $type = 'alnum' - options[alnum|numeric|]
     * 
     * @return Captcha
     */
    public function type(string $type = 'alnum') : Render;

    /**
     * Sets the character width.
     * 
     * @param int $param
     * 
     * @return Captcha
     */
    public function length(int $param) : Render;

    /**
     * Sets the character angle.
     * 
     * @param int $param
     * 
     * @return Captcha
     */
    public function angle(Float $param) : Render;

    /**
     * Add ttf fonts.
     * 
     * @param array $fonts
     * 
     * @return Captcha
     */
    public function ttf(array $fonts) : Render;

    /**
     * Sets the border color.
     * 
     * @param string $color = NULL
     * 
     * @return Captcha
     */
    public function borderColor(string $color = NULL) : Render;

    /**
     * Sets the background color.
     * 
     * @param string $color = NULL
     * 
     * @return Captcha
     */
    public function bgColor(string $color) : Render;

    /**
     * Add background pictures.
     * 
     * @param array $image
     * 
     * @return Captcha
     */
    public function bgImage(array $image) : Render;

    /**
     * Sets the text size.
     * 
     * @param int $size
     * 
     * @return Captcha
     */
    public function textSize(int $size) : Render;

    /**
     * Sets the text coordiante.
     * 
     * @param int $x
     * @param int $y
     * 
     * @return Captcha
     */
    public function textCoordinate(int $x, int $y) : Render;

    /**
     * Sets the text color.
     * 
     * @param string $color
     * 
     * @return Captcha
     */
    public function textColor(string $color) : Render;

    /**
     * Sets the grid color.
     * 
     * @param string $color
     * 
     * @return Captcha
     */
    public function gridColor(string $color = NULL) : Render;

    /**
     * Sets the grid space.
     * 
     * @param int $x = 0
     * @param int $y = 0
     * 
     * @return Captcha
     */
    public function gridSpace(int $x = 0, int $y = 0) : Render;

    /**
     * Completes the captcha creation process.
     * 
     * @param bool $img = false
     * 
     * @return string
     */
    public function create(bool $img = false) : string;

    /**
     * Returns the current captcha code.
     * 
     * @param void
     * 
     * @return string
     */
    public function getCode() : string;
}
