<?php namespace ZN\Ability;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

trait Functionalization
{
    /**
     * Magic call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public function __call($method, $parameters)
    {   
        # It allows a library to cluster the desired functions within it.
        if( $standart = (static::functionalization[strtolower($method)] ?? NULL) )
        {
            return $standart(...$parameters);
        }

        # The __call method of the parent class does not lose its functionality.
        if( method_exists(get_parent_class() ?: '', '__call'))
        {
            return parent::__call($method, $parameters);
        }
    }
}
