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

use ZN\IS;
use ZN\Request;
use ZN\Singleton;
use ZN\Buffering;

class AjaxBuilder
{
    /**
     * Protected keeps builder
     * 
     * @var string
     */
    protected $builder = NULL;

    /**
     * Protected keeps queue builder
     * 
     * @var string
     */
    protected $queueBuilder = NULL;

    /**
     * Protected script open tag
     * 
     * @var bool 
     */
    protected $tag = false;

    /**
     * Protected keeps ajax functions
     * 
     * @var array
     */
    protected $functions = 
    [
        'beforeSend', 
        'complete',
        'dataFilter',
        'error',
        'success',
        'xhr'
    ];

    /**
     * Protected keeps ajax queue method
     */
    protected $queues = 
    [
        'done',
        'then',
        'fail',
        'always'
    ];

    /**
     * Magic call
     * 
     * @param string $method
     * @param array  $parameter
     * 
     * @return object
     */
    public function __call($method, $parameter)
    {
        $value = $parameter[0];

        if( $method === 'url' )
        {
            $value = $this->getSiteURL($value);
        }

        if( in_array($method, $this->queues) )
        {
            $this->queueBuilder .= $this->queue($method, $value, $parameter[1] ?? 'data');
        }
        else
        {
            if( in_array($method, $this->functions) )
            {
                $option = $this->isCallableOption($value, $parameter[1] ?? 'data');
            }
            else
            {
                if( $method === 'data' && is_callable($value) )
                {
                    $option = Buffering\Callback::do($value);
                }
                else
                {
                    $option = json_encode($value); 
                }
            }

            $this->builder .= HT . $method . ':' . $option  . ',' . PHP_EOL;
        } 

        return $this;
    }

    /**
     * Magic to string
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->build($this->builder);
    }

    /**
     * Open script tag
     * 
     * @param bool $status
     */
    public function tag(Bool $status)
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
     * Protected build
     */
    protected function build(String $content)
    {
        $string  = '$.ajax({' . PHP_EOL;
        $string .= rtrim($content, ',' . PHP_EOL) . PHP_EOL;
        $string .= '})'.$this->queueBuilder.';' . PHP_EOL;

        $this->builder = NULL;
        $this->queueBuilder = NULL;

        return $this->openCloseTag($string);
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
     * Protected queue
     */
    protected function queue($type, $callback, $parameter)
    {
        $string  = PHP_EOL . '.' . $type . '(function(' . $parameter . '){' . PHP_EOL;
        $string .= HT . Buffering\Callback::do($callback);
        $string .= '})' . PHP_EOL;

        return $string;
    }

    /**
     * Protected get site url
     */
    protected function getSiteURL($value)
    {
        if( ! IS::url($value) )
        {
            $value = Request::getSiteURL($value);
        }

        return $value;
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
