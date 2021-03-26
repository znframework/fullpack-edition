<?php namespace ZN\Authentication;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Request\Request;

class IP
{
    /**
     * IP v4
     * 
     * @param void
     * 
     * @return string
     */
    public static function v4() : string
    {
        return Request::ipv4();
    }
}
