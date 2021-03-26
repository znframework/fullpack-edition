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
use ZN\Buffering;

class BuilderExtends
{
    /**
     * Protected keeps builder
     * 
     * @var string
     */
    protected $builder = NULL;

    /**
     * Protected script open tag
     * 
     * @var bool 
     */
    protected $tag = false;

    /**
     * Magic to string
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->openCloseTag($this->build($this->builder));
    }

    /**
     * Open script tag
     * 
     * @param bool $status
     */
    public function tag(bool $status)
    {
        $this->tag = $status;

        return $this;
    }

    /**
     * Protected get script class
     */
    protected function getScriptClass()
    {
        return Singleton::class('ZN\Hypertext\Script'); 
    }

    /**
     * Protected script open close tag
     */
    protected function openCloseTag($string)
    {
        if( $this->tag === true )
        {
            $script = $this->getScriptClass();

            $string = $script->open() . $string . $script->close();
        }

        $this->tag = false;

        return $string;
    }

    /**
     * Protected is callable option
     */
    protected function isCallableOption($callback, $parameter)
    {
        $option  = 'function(' . $parameter . '){' . PHP_EOL;
        $option .= Buffering\Callback::do($callback);
        $option .= '}';

        return $option;
    }
}
