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

class Agent
{
    /**
     * IP v4
     * 
     * @param void
     * 
     * @return string
     */
    public static function get() : String
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
}
