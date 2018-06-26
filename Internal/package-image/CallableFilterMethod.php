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

trait CallableFilterMethod
{
    /**
     * Keeps filters
     */
    protected $filters = 
    [
        'negate'     , 'grayscale'   , 'brightness'   ,
        'constrast'  , 'colorize'    , 'edgedetect'   ,
        'emboss'     , 'gaussianBlur', 'selectiveBlur',
        'meanRemoval', 'smooth'      , 'pixelate'
    ];

    /**
     * Magic call 
     * 
     * @param string $method
     * @param string $parameters
     * 
     * @return $this
     */
    protected function callable($method, $parameters)
    {
        if( in_array($method, $this->filters) )
        {
            $this->filter($method, ...$parameters);

            return $this;
        }
    }
}
