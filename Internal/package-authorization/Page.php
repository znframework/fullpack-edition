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

class Page extends PermissionExtends
{
    /**
     * Page
     * 
     * @param mixed $roleId   = NULL
     * @param array $table    = NULL
     * @param mixed $callback = NULL
     * 
     * @return mixed
     */
    public static function use($roleId = NULL, Array $table = NULL, $callback = NULL)
    {
        if( $roleId !== NULL && $table !== NULL )
        {
           return self::predefinedPermissionConfiguration($roleId, $table, $callback, 'page', [$roleId, NULL, NULL, 'page']);
        }

        return self::common(self::$roleId ?? $roleId, NULL, NULL, 'page');
    }
}
