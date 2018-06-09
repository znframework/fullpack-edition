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

use ZN\Structure;
use ZN\EventHandler\Exception\InvalidCallbackMethodException;

class Listener
{
    /**
     * Insert a listener.
     * 
     * @param string|callable $eventName
     * @param callable|int    $callback
     * @param int|null        $priority
     */
    public static function insert($eventName = NULL, $callback = NULL, $priority = NULL)
    { 
        if( $callback === NULL || is_int($callback) )
        {
            $priority  = $callback;
            $callback  = $eventName;
            $eventName = Properties::$startListenerName;
        }
        else
        {
            Properties::$startListenerName = $eventName;
        }

        $callback = self::callback($callback);
            
        if( ! is_callable($callback) )
        {
            throw new InvalidCallbackMethodException;
        }

        if( isset(Properties::$listeners[$eventName]) )
        {
            Properties::$listeners[$eventName][0][] = $priority;
			Properties::$listeners[$eventName][1][] = $callback;
        }
        else
        {
            Properties::$listeners[$eventName] = [[$priority], [$callback]];
        }
    }

    /**
     * Select a listener.
     * 
     * @param string $eventName
     * 
     * @return array
     */
    public static function select(String $eventName) : Array
    {
        return self::getListenersSortByPriority($eventName);
    }

    /**
     * Select all listeners.
     * 
     * @return array
     */
    public static function selectAll() : Array
    {
        return Properties::$listeners;
    }

    /**
     * Delete a listener.
     * 
     * @param string $eventName
     * @param mixed  $callback = NULL
     * 
     * @return bool
     */
    public static function delete(String $eventName, $callback = NULL) : Bool
    {
        if( $callback === NULL )
        {
            unset(Properties::$listeners[$eventName]);
        }
        else
        {
            $callback = self::callback($callback);

            if( isset(Properties::$listeners[$eventName]) )
            {
                foreach( Properties::$listeners[$eventName][1] as $key => $callable )
                {
                    if( print_r($callback, true) === print_r($callable, true) )
                    {
                        unset(Properties::$listeners[$eventName][0][$key]);
                        unset(Properties::$listeners[$eventName][1][$key]);

                        return true;
                    }
                }
            }

            return false;
        }     

        return true;
    }

    /**
     * Delete all listeners.
     * 
     * @return bool
     */
    public static function deleteAll() : Bool
    {
        Properties::$listeners = [];

        return true;
    }

    /**
     * Protected get listeners sort by priority
     * 
     * @return array
     */
    protected static function getListenersSortByPriority(String $eventName) : Array
    {
        if ( ! isset(Properties::$listeners[$eventName]))
		{
			return [];
        }
        
        array_multisort(Properties::$listeners[$eventName][0], SORT_NUMERIC, Properties::$listeners[$eventName][1]);

        return Properties::$listeners[$eventName][1];
    }

    /**
     * Protected mixed $callback
     * 
     * @return callable
     */
    protected static function callback($callback)
    {
        if( is_string($callback) && ! is_callable($callback) )
        {
            return self::controllerCallback($callback);
        }

        return $callback;
    }

    /**
     * Protected controller callback
     */
    protected static function controllerCallback($callback)
    {
        $datas      = Structure::data($callback);
        $function   = $datas['function'];
        $parameters = $datas['parameters'];
        $class      = $datas['namespace'] . $datas['page'];

        return function() use($class, $function, $parameters)
        { 
            (new $class)->$function(...$parameters); 
        };
    }
}