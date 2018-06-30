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

use ZN\IS;
use ZN\Config;
use ZN\Cryptography\Encode;

class Session implements SessionCookieCommonInterface
{
    use SessionCookieCommonTrait;

    /**
     * Insert session
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return bool
     */
    public function insert(String $name, $value) : Bool
    {
        $this->encodeNameValue($name, $value);

        $_SESSION[$name] = $value;

        if( $_SESSION[$name] )
        {
            $this->regenerateSessionId();
            $this->defaultVariable();

            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Select session
     * 
     * @param string $name
     * 
     * @return mixed
     */
    public function select(String $name)
    {
        $this->encodeNameValue($name);

        return $_SESSION[$name] ?? false;
    }

    /**
     * Select all session
     * 
     * @param void
     * 
     * @return array
     */
    public function selectAll() : Array
    {
        return $_SESSION;
    }

    /**
     * Delete session
     * 
     * @param string $name
     * 
     * @return bool
     */
    public function delete(String $name) : Bool
    {
        $this->encodeNameValue($name);

        if( isset($_SESSION[$name]) )
        {
            unset($_SESSION[$name]);

            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Delete all session
     * 
     * @param void
     * 
     * @return void
     */
    public function deleteAll() : Bool
    {
        return session_destroy();
    }
}
