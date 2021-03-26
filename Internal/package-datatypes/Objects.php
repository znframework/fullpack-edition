<?php namespace ZN\DataTypes;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use stdClass;

class Objects
{
    /**
     * Magic Constructor
     * 
     * @param array $array
     */
    public function __construct(array $array)
    {
        self::objectRecursive($array, $this);
    }

     /**
     * Protected Object Recursive
     */
    protected static function objectRecursive(array $array, &$std)
    {
        foreach( $array as $key => $value )
        {
            if( is_array($value) )
            {
                $std->$key = new stdClass;

                self::objectRecursive($value, $std->$key);
            }
            else
            {
                $std->$key = $value;
            }
        }
    }
}
