<?php namespace ZN\Storage;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface CookieInterface
{
    /**
     * Sets cookie time
     * 
     * @param int $time
     * 
     * @return Cookie
     */
    public function time(int $time) : Cookie;

    /**
     * Sets cookie path
     * 
     * @param string $path
     * 
     * @return Cookie
     */
    public function path(string $path) : Cookie;

    /**
     * Sets cookie domain
     * 
     * @param string @domain
     * 
     * @return Cookie
     */
    public function domain(string $domain) : Cookie;

    /**
     * Sets secure status
     * 
     * @param bool $secure = false
     * 
     * @return Cookie
     */
    public function secure(bool $secure) : Cookie;

    /**
     * Sets only http status
     * 
     * @param bool $httpOnly = true
     * 
     * @return Cookie
     */
    public function httpOnly(bool $httpOnly) : Cookie;
}
