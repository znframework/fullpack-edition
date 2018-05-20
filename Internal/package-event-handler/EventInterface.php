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

interface EventInterface
{
    /**
     * Run event by event name
     * 
     * @param string $eventName
     * @param array  $parameters
     * 
     * @return bool
     */
    public function run(String $eventName, Array $parameters = []) : Bool;

    /**
     * Insert a listener.
     * 
     * @param string|callable $eventName
     * @param callable|int    $callback
     * @param int|null        $priority
     */
    public static function insert($eventName = NULL, $callback = NULL, $priority = NULL) : Event;

    /**
     * Select a listener.
     * 
     * @param string $eventName
     * 
     * @return array
     */
    public static function selectListener(String $eventName) : Array;

    /**
     * Select all listeners.
     * 
     * @return array
     */
    public static function selectListeners() : Array;

    /**
     * Delete a listener.
     * 
     * @param string $eventName
     * @param mixed  $callback = NULL
     * 
     * @return bool
     */
    public static function deleteListener(String $eventName, $callback = NULL) : Bool;

    /**
     * Delete all listeners.
     * 
     * @return bool
     */
    public static function deleteListeners() : Bool;
}