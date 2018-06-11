<?php namespace ZN\Ability;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Singleton;

trait UnitTest
{
    /**
     * Protected unit methods
     * 
     * @var array
     */
    protected static $unitMethods = [];

    /**
     * Protected compares
     * 
     * @var array
     */
    protected static $compares = [];

    /**
     * Protected parameters
     * 
     * @var array
     */
    protected static $parameters    = [];

    /**
     * Protected fake parameters
     * 
     * @var array
     */
    protected static $fakeParameters    = [];

    /**
     * Magic call
     * 
     * @param string $method
     * @param array  $parameters
     */
    public function __call($method, $parameters)
    {
        $class = self::unitClass();

        self::$parameters[] = $parameters;

        return (new $class)->$method(...$parameters);
    }

    /**
     * Get result
     * 
     * @param string ...$method
     * 
     * @return string
     */
    public static function result(...$method)
    {
        if( $list = self::getCalledMethodList() )
        {
            self::runUnitMethods($list);
        }

        return self::getTestResult($method);
    }

    /**
     * Protected run unit methods
     */
    protected static function runUnitMethods($list)
    {
        $callClass = get_called_class();
            
        foreach( $list as $key => $met )
        {
            (new $callClass)->$met();

            $met = self::convertMultipleMethodName($met);

            self::$unitMethods[$met] = self::$parameters[$key] ?? self::$fakeParameters[$met] ?? [];
        }
    }

    /**
     * Protected get test result
     */
    protected static function getTestResult($method)
    {
        $tester = Singleton::class('ZN\Tester');
        
        $tester->class(self::unitClass())
               ->methods(self::getUnitMethods($method))
               ->compares(self::$compares)
               ->start();

        return $tester->result();
    }

    /**
     * Protected get unit methods
     */
    protected static function getUnitMethods($method)
    {
        $methods = self::unitMethods();

        if( ! empty($method) )
        {
            $oldMethods = $methods;
            $methods    = [];

            foreach( $method as $met )
            {
                $methods[$met] = $oldMethods[$met];
            }
        }

        return $methods;
    }

    /**
     * Protected unit class
     */
    protected static function unitClass()
    {
        if( defined('static::unit') )
        {
            $class = static::unit['class'] ?? NULL;
        }

        return $class ?? str_replace('\\Tests\\', '\\', get_called_class());
    }

    /**
     * Protected unit methods
     */
    protected static function unitMethods()
    {
        $methods = [];

        if( defined('static::unit') )
        {
            $methods = static::unit['methods'];
        }

        return $methods + self::$unitMethods;
    }

    /**
     * Protected get called method list
     */
    protected static function getCalledMethodList()
    {
        $currentMethods = get_class_methods(__CLASS__);

        $methods = get_class_methods(get_called_class());

        return array_diff($methods, $currentMethods);
    }

    /**
     * Protected compare
     */
    protected function compare($first, $second)
    {
        $debug  = debug_backtrace(); 
        $method = $debug[1]['function'];
        $method = self::convertMultipleMethodName($method);

        self::$fakeParameters[$method] = $debug[0]['args'] ?? [];
       
        self::$compares[$method] = $first === $second;
    }
    
    /**
     * Protected convert multiple method name
     */
    protected static function convertMultipleMethodName($name)
    {
        if( preg_match('/(?<method>\w+)(?<count>[0-9]+)/', $name, $match) )
        {
            $name = $match['method'] . ':' . $match['count'] ;
        }

        return ltrim($name, '_');
    }
}
