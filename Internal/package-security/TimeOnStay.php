<?php namespace ZN\Security;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class TimeOnStay
{
    /**
     * Create time to stay on the page
     * 
     * @param string $name = 'timeOnStay'
     */
    public static function create(String $name = 'timeOnStay')
    {
        $_SESSION[$name] = time();
    }

    /**
     * Valid time to stay on the page
     * 
     * @param int    $time = 5
     * @param string $name = 'timeOnStay'
     * 
     * @return bool
     */
    public static function valid(Int $time = 5, String $name = 'timeOnStay') : Bool
    {
        if( empty($_SESSION[$name]) )
        {
            return false;
        }

        $pagetime = time() - $_SESSION[$name];

        if( $pagetime >= $time )
        {
            return true;
        }

        return false;
    }
}
