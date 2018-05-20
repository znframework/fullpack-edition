<?php namespace ZN\EventHandler;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Run
{
    /**
     * Run event by event name
     * 
     * @param string $eventName
     * @param array  $parameters
     * 
     * @return bool
     */
    public static function event(String $eventName, Array $parameters) : Bool
    {
        $events = Listener::select($eventName);

        $return = NULL;

        foreach( $events as $event )
        {
            $return = $event(...$parameters);
        }

        if( $return === false )
        {
            return false;
        }

        return true;
    }
}