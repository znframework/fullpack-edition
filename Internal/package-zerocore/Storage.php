<?php namespace ZN;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Storage
{   
    /**
     * Start Session
     */
    public static function start()
    {
        if( ! session_id() )
        {
            session_start();
        }
    }
}