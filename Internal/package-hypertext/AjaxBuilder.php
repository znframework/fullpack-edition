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
use ZN\Buffering;

class AjaxBuilder extends BuilderExtends
{
    /**
     * Protected keeps queue builder
     * 
     * @var string
     */
    protected $queueBuilder = NULL;

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
        $value = $parameter[0] ?? '';

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
     * Protected build
     */
    protected function build(string $content)
    {
        $string  = '$.ajax({' . PHP_EOL;
        $string .= rtrim($content, ',' . PHP_EOL) . PHP_EOL;
        $string .= '})'.$this->queueBuilder.';' . PHP_EOL;

        $this->builder = NULL;
        $this->queueBuilder = NULL;

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
}
