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

use ZN\Base;
use ZN\Classes;
use ZN\Datatype;
use ZN\DataTypes\Arrays;

trait CallableElements
{
    protected $useElements =
    [
        'addclass' => 'class'
    ];

    /**
     * Magic Call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $realMethod = $method;
        $method     = strtolower($method);
        $className  = Classes::onlyName(__CLASS__);

        if( $className === 'Html')
        {
            $multiElement = $this->elements['multiElement'];

            # Bootstrap Gridsystem
            if( $this->isBootstrapColumn($method, $match) )
            {
                $this->bootstrapColumn($parameters[0] ?? '', $match);

                return $this;
            }
            
            # Bootstrap Alert
            elseif( strpos($method, 'alert') === 0 )
            {
                return $this->alert(Base::removePrefix($method, 'alert'), $parameters[0] ?? '');
            }

            # Multiple Element
            elseif( array_key_exists($method, $multiElement) )
            {
                $realMethod = $multiElement[$method];

                return $this->_multiElement($realMethod, ...$parameters);
            }
            elseif( in_array($method, $multiElement) )
            {
                return $this->_multiElement($realMethod, ...$parameters);
            }

            # Single Element
            elseif( in_array($method, $this->elements['singleElement']) )
            {
                return $this->_singleElement($realMethod, ...$parameters);
            }

            # Media Content
            elseif( in_array($method, $this->elements['mediaContent']) )
            {
                return $this->_mediaContent($parameters[0] ?? '', $parameters[1] ?? NULL, $parameters[2] ?? [], $realMethod);
            }

            # Media
            elseif( in_array($method, $this->elements['media']) )
            {
                return $this->_media($parameters[0] ?? '', $parameters[1] ?? [], $realMethod);
            }

            # Content Attribute
            elseif( in_array($method, $this->elements['contentAttribute']) )
            {
                return $this->_contentAttribute($parameters[0] ?? '', $parameters[1] ?? [], $realMethod);
            }

            # Content
            elseif( in_array($method, $this->elements['content']) )
            {
                return $this->_content($parameters[0] ?? '', $realMethod);
            }
        }
        elseif( $className === 'Form' )
        {
            if( in_array($method, $this->elements['input']) )
            {
                return $this->_input($parameters[0] ?? '', $parameters[1] ?? '', $parameters[2] ?? [], $realMethod);
            }
        }

        if( empty($parameters) )
        {
            $parameters[0] = $method;
        }
        else
        {
            if( $parameters[0] === NULL )
            {
                return $this;
            }
        }

        if( isset($this->useElements[$method]) )
        {
            $method = $this->useElements[$method];
        }

        # Convert exampleData to example-data [4.6.1]
        if( ! ctype_lower($realMethod) )
        {
            $newMethod = NULL;
            $split     = Datatype::splitUpperCase($realMethod);
            $method    = implode('-', Arrays\Casing::lower($split));
        }

        $this->_element($method, ...$parameters);

        return $this;
    }
}
