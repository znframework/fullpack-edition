<?php namespace ZN\Security;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Security;

class CrossSiteRequestForgery
{
    /**
     * Cross Site Request Forgery
     * 
     * @param string $uri  = NULL
     * @param string $type = 'post'
     * 
     * @return void
     */
    public static function token(string $uri = NULL, string $type = 'post')
    {
        Security::CSRFToken($uri, $type);
    }

    /**
     * Get
     * 
     * @param string $uri = NULL
     * 
     * @return void
     */
    public static function get(string $uri = NULL)
    {
        self::token($uri, 'get');
    }

    /**
     * Get string token
     * 
     * @param string $name = 'token'
     * 
     * @return string
     */
    public static function key(string $name = 'token')
    {
        Security::createCSRFTokenKey($name);

        return Security::getCSRFTokenKey($name);
    }

    /**
     * Valid token
     * 
     * @param string $name  = 'token'
     * @param string $type  = 'post'
     * 
     * @return bool
     */
    public static function validToken(string $name = 'token', string $type = 'post') : bool
    {
        return Security::validCSRFToken($name, $type);
    }
}
