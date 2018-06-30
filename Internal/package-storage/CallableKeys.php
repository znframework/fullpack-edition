<?php namespace ZN\Storage;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\DataTypes\Arrays;
use ZN\DataTypes\Strings;

trait CallableKeys
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
        $split = Strings\Split::upperCase($method);

        if( Arrays\GetElement::last($split) === 'Delete' )
        {
            $method = 'delete';

            return $this->delete($split[0]);
        }

        if( $method === 'all' )
        {
            $method = 'selectAll';

            return $this->$method();
        }

        if( $param = ($parameters[0] ?? NULL) )
        {
            return $this->insert($method, $param);
        }

        return $this->select($method);
    }
}
