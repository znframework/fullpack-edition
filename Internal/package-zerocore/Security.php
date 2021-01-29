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
     * @return string 
     */
    public static function getCSRFTokenKey($key = 'token')
    {
        return $_SESSION[$key];
    }

    /**
     * Creates CSRF Token Key
     */
    public static function createCSRFTokenKey($key = 'token')
    {
       $_SESSION[$key] = self::createHashCode();
    }

    /**
     * Cross Site Request Forgery
     * 
     * @param string $uri  = NULL
     * @param string $type = 'post'
     * 
     * @return void
     */
    public static function CSRFToken(String $uri = NULL, String $type = 'post', $key = 'token')
    {
        switch( $type )
        {
            case 'post': $method = $_POST; break;
            case 'get' : $method = $_GET;  break;
        }

        if( $method ?? NULL )
        {
            Storage::start();

            $mtoken = $method[$key]   ?? self::createHashCode(16);
            $stoken = $_SESSION[$key] ?? self::createHashCode(8);

            if( $mtoken !== $stoken )
            {
                Response::redirect($uri);
            }
        }
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