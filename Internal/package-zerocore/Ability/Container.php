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

use ZN\Datatype;
use ZN\Singleton;
use ZN\Ability\Exception\InvalidContainerMethod; 
use ZN\Ability\Exception\UnsupportedDriverException;

trait Container
{
    /**
     * Keeps class interface..
     * 
     * @var StorageInterface
     */
    protected static $container;

    /**
     * Magic call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return self::call($method, $parameters);
    }

    /**
     * Magic call static
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return self::call($method, $parameters);
    }

    /**
     * Get driver
     * 
     * @param string $class
     * 
     * @return ZN\Singleton
     */
    public static function driver(string $class)
    {
        $class = Datatype::divide(__CLASS__, '\\', 0, -1) . ucfirst($origin = $class);

        if( ! class_exists($class) )
        {
            throw new UnsupportedDriverException(NULL, ['%' => $origin, '#' => __CLASS__]);
        }

        return Singleton::class($class); // @codeCoverageIgnore
    }

    /**
     * Protected static call
     */
    protected static function call($method, $parameters)
    {
        if( method_exists(self::$container, $method) )
        {
            return self::$container->$method(...$parameters);
        }

        throw new InvalidContainerMethod(NULL, ['%' => $method, '#' => __CLASS__]);
    }
}
