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

class JQueryBuilder extends BuilderExtends
{
    /**
     * Protected keeps selector
     * 
     * @var string
     */
    protected $selector;

    /**
     * Magic call
     * 
     * @param string $method
     * @param array  $parameter
     * 
     * @return object
     */
    public function __call($method, $parameters)
    {  
        $this->builder .= '.' . $method . '(';

        foreach( $parameters as $parameter )
        {
            if( is_callable($parameter) )
            {
                $option = $this->isCallableOption($parameter, 'data');
            }
            else
            {
                $option = json_encode($parameter); 
            }

            $this->builder .= $option  . ', ';
        }

        $this->builder = rtrim($this->builder, ', ');

        $this->builder .= ')';

        return $this;
    }

    /**
     * Keeps jquery selector
     * 
     * @param string $selector
     * 
     * @return object
     */
    public function selector($selector)
    {
        if( is_scalar($selector) )
        {
            $this->selector = json_encode($selector);
        }
        else
        {
            $this->selector = Buffering\Callback::do($selector);
        }
        

        return $this;
    }

    /**
     * Protected build
     */
    protected function build(String $content)
    {
        $string = '$(' . $this->selector . ')' . $content . ';';

        $this->builder = NULL;

        return $this->openCloseTag($string);
    }
}
