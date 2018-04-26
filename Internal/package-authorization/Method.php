<?php namespace ZN\Authorization;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Method extends PermissionExtends
{
    /**
     * Post
     * 
     * @param mixed $roleId   = NULL
     * @param array $table    = NULL
     * @param mixed $callback = NULL
     * 
     * @return bool
     */
    public static function post($roleId = NULL, Array $table = NULL, $callback = NULL) : Bool
    {
        return self::use($roleId, __FUNCTION__, $table, $callback);
    }

    /**
     * Get
     * 
     * @param mixed $roleId   = NULL
     * @param array $table    = NULL
     * @param mixed $callback = NULL
     * 
     * @return bool
     */
    public static function get($roleId = NULL, Array $table = NULL, $callback = NULL) : Bool
    {
        return self::use($roleId, __FUNCTION__, $table, $callback);
    }

    /**
     * Request
     * 
     * @param mixed $roleId   = NULL
     * @param array $table    = NULL
     * @param mixed $callback = NULL
     * @return bool
     */
    public static function request($roleId = NULL, Array $table = NULL, $callback = NULL) : Bool
    {
        return self::use($roleId, __FUNCTION__, $table, $callback);
    }

    /**
     * Method
     * 
     * @param mixed  $roleId   = NULL
     * @param string $method   = 'post'
     * @param array  $table    = NULL
     * @param mixed  $callback = NULL
     * 
     * @return bool
     */
    public static function use($roleId = NULL, $method = 'post', Array $table = NULL, $callback = NULL) : Bool
    {
        if( $roleId !== NULL && $table !== NULL )
        {
            return self::predefinedPermissionConfiguration($roleId, $table, $callback, 'method', [$roleId, $method, NULL, 'method']);
        }

        return self::common(self::$roleId ?? $roleId, $method, NULL, 'method');
    }
}
