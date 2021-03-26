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

use ZN\Protection\Json;
use ZN\Storage\Exception\SetcookieException;

class Cookie implements CookieInterface, StorageInterface
{
    use StorageCommonMethods;

    /**
     * Keeps time
     * 
     * @var int
     */
    protected $time;

    /**
     * Keeps path
     * 
     * @var string
     */
    protected $path;

    /**
     * Keeps domain
     * 
     * @var string
     */
    protected $domain;

    /**
     * Keeps secure status
     * 
     * @var bool
     */
    protected $secure;

    /**
     * Keeps http status
     * 
     * @var bool
     */
    protected $httpOnly;

    /**
     * Sets cookie time
     * 
     * @param int $time
     * 
     * @return Cookie
     */
    public function time(int $time) : Cookie
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Sets cookie path
     * 
     * @param string $path
     * 
     * @return Cookie
     */
    public function path(string $path) : Cookie
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Sets cookie domain
     * 
     * @param string @domain
     * 
     * @return Cookie
     */
    public function domain(string $domain) : Cookie
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Sets secure status
     * 
     * @param bool $secure = false
     * 
     * @return Cookie
     */
    public function secure(bool $secure = false) : Cookie
    {
        $this->secure = $secure;

        return $this;
    }

    /**
     * Sets only http status
     * 
     * @param bool $httpOnly = true
     * 
     * @return Cookie
     */
    public function httpOnly(bool $httpOnly = true) : Cookie
    {
        $this->httpOnly = $httpOnly;

        return $this;
    }

    /**
     * Insert cookie
     * 
     * @param string $name
     * @param mixed  $value
     * @param int    $time = NULL
     * 
     * @return bool
     */
    public function insert(string $name, $value, int $time = NULL) : bool
    {
        if( ! empty($time) ) $this->time($time);

        $this->encodeNameValue($name, $value);

        $this->addType($_COOKIE, $name, $value);

        $this->setParameters();

        if( ! is_scalar($value) )
        {
            $value = json_encode($value);
        }
        
        // @codeCoverageIgnoreStart
        if( setcookie($name, $value, time() + $this->time, $this->path, $this->domain, $this->secure, $this->httpOnly) )
        {
            $this->regenerateSessionId();
            $this->defaultVariable();
            $this->cookieDefaultVariable();

            return true;
        }
        // @codeCoverageIgnoreEnd
        else
        {
            throw new SetcookieException;
        }
    }

    /**
     * Select cookie
     * 
     * @param string $name
     * 
     * @return mixed
     */
    public function select(string $name)
    {
        $this->encodeNameValue($name);

        if( isset($_COOKIE[$name]) )
        {
            return $this->getScalarCookieContentByName($name);
        }
        else
        {
            return false;
        }
    }

    /**
     * Select all cookie
     * 
     * @param void
     * 
     * @return array
     */
    public function selectAll() : array
    {
        if( ! empty($_COOKIE) )
        {
            return $_COOKIE;
        }
        else
        {
            return [];
        }
    }

    /**
     * Delete cookie
     * 
     * @param string $name
     * @param string $path = NULL
     * 
     * @param bool
     */
    public function delete(string $name, string $path = NULL) : bool
    {
        $this->setCookiePath($path);

        $this->encodeNameValue($name);

        if( isset($_COOKIE[$name]) )
        {
            setcookie($name, '', (time() - 1), $this->path);

            $this->path = NULL;

            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Delete all cookies
     * 
     * @param void
     * 
     * @return bool
     */
    public function deleteAll() : bool
    {
        $path = $this->config['path'];

        if( ! empty($_COOKIE) ) 
        {
            foreach( $_COOKIE as $key => $val )
            {
                setcookie($key, '', time() - 1, $path);
            }

            $_COOKIE = [];
        }
        else
        {
            return false; // @codeCoverageIgnore
        }

        return true;
    }

    /**
     * Protected set parameters
     */
    protected function setParameters()
    {
        if( empty($this->time    ) ) $this->time     = $this->config['time'];
        if( empty($this->path    ) ) $this->path     = $this->config['path'];
        if( empty($this->domain  ) ) $this->domain   = $this->config['domain'];
        if( empty($this->secure  ) ) $this->secure   = $this->config['secure'];
        if( empty($this->httpOnly) ) $this->httpOnly = $this->config['httpOnly'];
    }

    /**
     * Protected set cookie path
     */
    protected function setCookiePath($path = NULL)
    {
        if( empty($this->path) )
        {
            $this->path = $path ?? $this->config["path"];
        }
    }

    /**
     * Protected get scalar cookie content
     */
    protected function getScalarCookieContentByName($name)
    {
        return ! Json::check($_COOKIE[$name]) ? $_COOKIE[$name] : json_decode($_COOKIE[$name], true);
    }

    /**
     * Default Cookie Variable
     * 
     * @codeCoverageIgnore
     */
    protected function cookieDefaultVariable()
    {
        $this->time     = NULL;
        $this->path     = NULL;
        $this->domain   = NULL;
        $this->secure   = NULL;
        $this->httpOnly = NULL;
    }
}
