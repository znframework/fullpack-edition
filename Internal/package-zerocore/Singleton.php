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

class Singleton
{
    /**
     * singleton
     * 
     * @var self
     * 
     * @return self
     */
    private static $singleton = NULL;
    
    /**
     * singleton
     * 
     * 5.7.4.4|5.7.4.5|5.7.4.6[changed]
     * 
     * @param string $class
     * 
     * @return self
     */
    public static function class(string $class)
    {
        if( ! isset(self::$singleton[$class]) ) 
        {
            self::$singleton[$class] = self::createNewInstance($class);
        }

        return self::$singleton[$class];
    }

    /**
     * Protected craete new instance
     */
    private static function createNewInstance($class)
    {
        if( ! class_exists($class) )
        {
            $classInfo = Autoloader::getClassFileInfo($class);

            $class = $classInfo['namespace'] ?: $class;
        }

        return new $class;
    }
}