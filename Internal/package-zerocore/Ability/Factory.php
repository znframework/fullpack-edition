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

use ZN\Support;
use ZN\Singleton;

trait Factory
{
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
     * Magic call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->call($method, $parameters);
    }

    /**
     * Magic call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public static function call($method, $parameters)
    {
        if( ! defined('static::factory') )
        {
            return false;
        }

        $method = strtolower($originMethodName = $method);

        if( ! isset(static::factory['methods'][$method]) )
        {
            Support::classMethod(get_called_class(), $originMethodName);
        }

        # The subclass and method that the method will execute is taken.
        $class = static::factory['methods'][$method];

        # The class to be used as factory for the library used is defined.
        # However, this usage is not necessary.
        $factory = static::factory['class'] ?? NULL;

        if( $factory !== NULL )
        {
            return $factory::class($class)->$method(...$parameters);
        }
        # It can call the desired method of another class.
        # That is, it opens the way to a mixed class design 
        # that consists of methods of various classes.
        else
        {
            # Solving starts when a valid class and method information is sent.
            if( ! self::isValidClassAndMethodName($class, $resolve) )
            {
                throw new Exception\InvalidFactoryMethod(NULL, $class);
            }

            # A new singleton inheritance class instance is created.
            $return = self::createSingletonInstance($resolve['class'], $resolve['method'], $parameters);

            # The return value $this can be sent to ensure object continuity.
            if( isset($resolve['this']) )
            {
                return new self;
            }

            # Return new instance.
            return $return;
        }
    }

    /**
     * Protected create singleton instance
     */
    protected static function createSingletonInstance($class, $method, $parameters)
    {
        return Singleton::class(self::getCalledClassNamespace() . $class)->$method(...$parameters);
    }

    /**
     * Protected get called class namespace
     */
    protected static function getCalledClassNamespace()
    {
        $namespace = NULL;

        # The namespace is being rebuilt.
        if( strstr($calledClass = get_called_class(), $separator = '\\') )
        {
            $namespace = explode($separator, $calledClass);
            
            array_pop($namespace);

            $namespace = implode($separator, $namespace) . $separator;
        }

        return $namespace;
    }

    /**
     * Protected is valid class & method name.
     */
    protected static function isValidClassAndMethodName($class, &$resolve)
    {
        return preg_match('/(?<class>([a-zA-Z]\w+(\\\\)*){1,})\:\:(?<method>\w+)(?<this>\:this)*/', $class, $resolve);
    }
}
