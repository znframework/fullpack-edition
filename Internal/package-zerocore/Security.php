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

class Security
{   
    /**
     * Get CSRF Token Key
     * 
     * @param string $name = 'token'
     * 
     * @return string 
     */
    public static function getCSRFTokenKey(String $name = 'token')
    {
        return $_SESSION[$name];
    }

    /**
     * Creates CSRF Token Key
     * 
     * @param string $name = 'token'
     */
    public static function createCSRFTokenKey(String $name = 'token')
    {
       $_SESSION[$name] = self::createHashCode();
    }

    /**
     * Cross Site Request Forgery
     * 
     * @param string $uri  = NULL
     * @param string $type = 'post'
     * 
     * @return void
     */
    public static function CSRFToken(String $uri = NULL, String $type = 'post', $name = 'token')
    {
        if( ! self::validCSRFToken($name, $type) )
        {
            Response::redirect($uri);
        }
    }

    /**
     * Cross Site Request Forgery
     * 
     * @param string $name = NULL
     * @param string $type = 'post'
     * 
     * @return bool
     */
    public static function validCSRFToken(String $name = 'token', String $type = 'post')
    {
        switch( $type )
        {
            case 'post': $method = $_POST; break;
            case 'get' : $method = $_GET;  break;
        }

        Storage::start();

        $mtoken = $method[$name]   ?? self::createHashCode(16);
        $stoken = $_SESSION[$name] ?? self::createHashCode(8);

        if( $mtoken !== $stoken )
        {
            return false;
        }

        return true;
    }

    /**
     * Create Hash Code
     * 
     * @param int $len = 32
     * 
     * @return string
     */
    protected static function createHashCode(Int $len = 32)
    {
        return bin2hex(random_bytes($len));
    }
}