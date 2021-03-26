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

use ReflectionClass;

class Classes
{
    /**
     * Reflection Class
     * 
     * @param string $className
     * 
     * @return ReflectionClass
     */
    public static function reflection(string $className) : ReflectionClass
    {
        return new ReflectionClass(self::_class($className));
    }

    /**
     * Is Relation 
     * 
     * @param string $className
     * @param mixed  $object
     * 
     * @return bool
     */
    public static function isRelation(string $className, $object) : bool
    {
        if( ! is_object($object) )
        {
            throw new Exception\InvalidArgumentException('Error', 'objectParameter', '2.($object)');
        }

        return is_a($object, self::_class($className));
    }

    /**
     * Is Parent 
     * 
     * @param string $className
     * @param mixed  $object
     * 
     * @return bool
     */
    public static function isParent(string $className, $object) : bool
    {
        return is_subclass_of($object, self::_class($className));
    }

    /**
     * Method Exists
     * 
     * @param string $className
     * @param string $method
     * 
     * @return bool
     */
    public static function methodExists(string $className, string $method) : bool
    {
        return method_exists(Singleton::class(self::_class($className)), $method);
    }

    /**
     * Property Exists
     * 
     * @param string $className
     * @param string $property
     * 
     * @return bool
     */
    public static function propertyExists(string $className, string $property) : bool
    {
        return  property_exists(Singleton::class(self::_class($className)), $property);
    }

    /**
     * Get Methods
     * 
     * @param string $className
     * 
     * @return bool
     */
    public static function methods(string $className) : array
    {
        return get_class_methods(self::_class($className));
    }

    /**
     * Get Vars
     * 
     * @param string $className
     * 
     * @return bool
     */
    public static function vars(string $className) : array
    {
        return get_class_vars(self::_class($className));
    }

    /**
     * Get Class Name
     * 
     * @param object $var
     * 
     * @return string
     */
    public static function name($var) : string
    {
        if( ! is_object($var) )
        {
            return false;
        }

        return get_class($var);
    }

    /**
     * Get Declared Classes
     * 
     * @return array
     */
    public static function declared() : array
    {
        return get_declared_classes();
    }

    /**
     * Get Declared Interfaces
     * 
     * @return array
     */
    public static function declaredInterfaces() : array
    {
        return get_declared_interfaces();
    }

    /**
     * Get Declared Traits
     * 
     * @return array
     */
    public static function declaredTraits() : array
    {
        return get_declared_traits();
    }

/**
     * Get Only Class Name
     * 
     * @param string $class
     * 
     * @return string
     */
    public static function onlyName(string $class) : string
    {
        return Datatype::divide(str_replace(INTERNAL_ACCESS, '', $class), '\\', -1);
    }

    /**
     * Get Class Name
     * 
     * @param string $clasName
     * 
     * @return string
     */
    public static function class(string $className) : string
    {
        return self::_class($className);
    }

    /**
     * Protected Class
     */
    protected static function _class($name)
    {
        global $classMap;

        Config::get('ClassMap');

        $lowerName           = strtolower($name);
        $lowerInternalAccess = strtolower(INTERNAL_ACCESS);
        $flipClassMap        = array_flip($classMap['namespaces'] ?? []);
        $lowerClass          = $lowerInternalAccess.$lowerName;

        
        if( ! empty($flipClassMap[$lowerName]) )
        {
            return $flipClassMap[$lowerName]; // @codeCoverageIgnore
        }
        elseif( ! empty($flipClassMap[$lowerClass]) )
        {
            return $flipClassMap[$lowerClass]; // @codeCoverageIgnore
        }
        elseif( ! empty($classMap['classes'][$lowerClass]) )
        {
            return $classMap['classes'][$lowerClass]; // @codeCoverageIgnore
        }
        else
        {
            return $name;
        }
    }
}
