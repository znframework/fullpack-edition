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

class Event implements EventInterface
{
    /**
     * Event Callable
     */
    use EventCallable;

    /**
     * Run event by event name
     * 
     * @param string $eventName
     * @param array  $parameters
     * 
     * @return bool
     */
    public static function run(String $eventName, Array $parameters = []) : Bool
    {
        return Run::event($eventName, $parameters);
    }

    /**
     * Insert a listener.
     * 
     * @param string|callable $eventName
     * @param callable|int    $callback
     * @param int|null        $priority
     */
    public static function insert($eventName = NULL, $callback = NULL, $priority = NULL) : Event
    {
        Listener::insert($eventName, $callback, $priority);

        return new self;
    }

    /**
     * Select a listener.
     * 
     * @param string $eventName
     * 
     * @return array
     */
    public static function selectListener(String $eventName) : Array
    {
        return Listener::select($eventName);
    }

    /**
     * Select all listeners.
     * 
     * @return array
     */
    public static function selectListeners() : Array
    {
        return Listener::selectAll();
    }

    /**
     * Delete a listener.
     * 
     * @param string $eventName
     * @param mixed  $callback = NULL
     * 
     * @return bool
     */
    public static function deleteListener(String $eventName, $callback = NULL) : Bool
    {
        return Listener::delete($eventName, $callback);
    }

    /**
     * Delete all listeners.
     * 
     * @return bool
     */
    public static function deleteListeners() : Bool
    {
        return Listener::deleteAll();
    }
}