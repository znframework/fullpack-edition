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
     * @param string $event
     * @param array  $parameters
     * 
     * @return bool
     */
    public static function run(string $event, array $parameters = []) : bool;

    /**
     * Insert a listener.
     * 
     * @param callable|int    $callback
     * @param int|null        $priority
     */
    public static function callback($callback = NULL, $priority = NULL) : Event;

    /**
     * Insert a listener.
     * 
     * @param string $event
     */
    public static function create($event = NULL);

    /**
     * Select a listener.
     * 
     * @param string $event
     * @param int    $priority = NULL
     * 
     * @return array|callback
     */
    public static function get(string $event, int $priority = NULL);

    /**
     * Delete a listener.
     * 
     * @param string $event
     * @param int    $priority = NULL
     * 
     * @return bool
     */
    public static function remove(string $event, int $priority = NULL) : bool;
}