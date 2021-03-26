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

class Session implements StorageInterface
{
    use StorageCommonMethods;

    /**
     * Insert session
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return bool
     */
    public function insert(string $name, $value) : bool
    {
        $this->encodeNameValue($name, $value);

        $this->addType($_SESSION, $name, $value);

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
    public function select(string $name)
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
    public function selectAll() : array
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
    public function delete(string $name) : bool
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
    public function deleteAll() : bool
    {
        $_SESSION = [];

        return session_destroy();
    }
}
