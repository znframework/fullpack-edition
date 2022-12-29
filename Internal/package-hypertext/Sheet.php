<?php namespace ZN\Hypertext;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Singleton;

/**
 * @codeCoverageIgnore
 */
class Sheet
{
    /**
     * selector
     * 
     * @var string
     */
    protected $selector = 'this';
    
    /**
     * attr
     * 
     * @var array
     */
    protected $attr;

    /**
     * tag
     * 
     * @var bool
     */
    protected $tag = false;

    /**
     * Magic construct
     * 
     * @param bool $tag = false
     * 
     * @return void
     */
    public function __construct($tag = false)
    {
        $this->tag = $tag;
    }

    /**
     * Sets attributes.
     * 
     * @param array $attributes
     * 
     * @return $this
     */
    public function attr(array $attributes)
    {
        $this->attr = $this->attrCreator($attributes);

        return $this;
    }

    /**
     * Sets selector.
     * 
     * @param string $selector
     * 
     * @return $this
     */
    public function selector(string $selector)
    {
        $this->selector = $selector;

        return $this;
    }

    /**
     * Creates element.
     * 
     * @param string ...$args
     * 
     * @return string
     */
    public function create(...$args) : string
    {
        $combineTransitions = $args;

        $str  = $this->selector . '{';

        if( ! empty($this->attr) )
        {
            $str .= EOL . $this->attr . EOL;
        } 

        if( ! empty($combineTransitions) ) foreach( $combineTransitions as $transition )
        {
            $str .= $transition;
        }

        $str .= '}' . EOL;

        return $this->tagCreator($str);
    }

    /**
     * protected tag
     * 
     * @param string $code
     * 
     * @return string
     */
    protected function tagCreator($code)
    {
        if( $this->tag === true )
        {
            $style = Singleton::class('ZN\Hypertext\Style');

            return $style->open() . $code . $style->close();
        }

        $this->defaultVariables();

        return $code;
    }

    /**
     * protected attr
     * 
     * @param array $attributes = []
     * 
     * @return string
     */
    protected function attrCreator($attributes = [])
    {
        $attribute = '';

        if( is_array($attributes) )
        {
            foreach( $attributes as $key => $values )
            {
                if( is_numeric($key) )
                {
                    $key = $values;
                }

                $attribute .= ' ' . $key . ':' . $values . ';';
            }
        }

        return $attribute;
    }

    /**
     * protected default variables
     * 
     * @param void
     * 
     * @return void
     */
    protected function defaultVariables()
    {
        $this->attr        = NULL;
        $this->selector    = 'this';
    }
}
