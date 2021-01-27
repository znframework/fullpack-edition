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

use ZN\EventHandler\Exception\InvalidCallbackMethodException;

class Event implements EventInterface
{
    /**
     * Event Callable
     */
    use EventCallable;

    /**
     * Run event by event name
     * 
     * @param string $event
     * @param array  $parameters
     * 
     * @return bool
     */
    public static function run(String $event, Array $parameters = []) : Bool
    {
        $events = self::get($event);
        
        $return = NULL;

        foreach( $events as $e )
        {
            $return = $e(...$parameters);
        }

        if( $return === false )
        {
            return false;
        }

        return true;
    }

    /**
     * Insert a listener.
     * 
     * @param callable|int    $callback
     * @param int|null        $priority
     */
    public static function callback($callback = NULL, $priority = NULL) : Event
    {
        if( ! is_callable($callback) )
        {
            throw new InvalidCallbackMethodException;
        }

        if( $priority )
        {
            Properties::$listener[$priority] = $callback;
        }
        else
        {
            Properties::$listener[] = $callback;
        }

        return new self;
    }

    /**
     * Insert a listener.
     * 
     * @param string $event
     */
    public static function create($event = NULL)
    { 
        $listener = Properties::$listener;

        Properties::$listener = [];

        Properties::$listeners[$event] = $listener;

        return new self;
    }

    /**
     * Select a listener.
     * 
     * @param string $event
     * @param int    $priority = NULL
     * 
     * @return array|callback
     */
    public static function get(String $event, Int $priority = NULL)
    {
        if ( ! isset(Properties::$listeners[$event]))
		{
			return [];
        }

        $listeners = Properties::$listeners[$event];
        
        ksort($listeners);

        if( $priority )
        {
            return $listeners[$priority] ?? [];
        }

        return $listeners;
    }

    /**
     * Delete a listener.
     * 
     * @param string $event
     * @param int    $priority = NULL
     * 
     * @return bool
     */
    public static function remove(String $event, Int $priority = NULL) : Bool
    {
        if( isset(Properties::$listeners[$event][$priority]) )
        {
            unset(Properties::$listeners[$event][$priority]);

            return true;
        }

        if( $priority === NULL && isset(Properties::$listeners[$event]) )
        {
            unset(Properties::$listeners[$event]);

            return true;
        }

        return false;
    }
}