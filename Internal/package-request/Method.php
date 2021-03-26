<?php namespace ZN\Request;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\In;
use ZN\Protection\Json;

class Method implements MethodInterface
{
    /**
     * Post
     * 
     * @param string $name  = NULL
     * @param mixed  $value = NULL
     * 
     * @return mixed
     */
    public static function post(string $name = NULL, $value = NULL)
    {
        return self::_method($name, $value, $_POST ,__FUNCTION__);
    }

    /**
     * Get
     * 
     * @param string $name  = NULL
     * @param mixed  $value = NULL
     * 
     * @return mixed
     */
    public static function get(string $name = NULL, $value = NULL)
    {
        return self::_method($name, $value, $_GET, __FUNCTION__);
    }

    /**
     * Request
     * 
     * @param string $name  = NULL
     * @param mixed  $value = NULL
     * 
     * @return mixed
     */
    public static function request(string $name = NULL, $value = NULL)
    {
        return self::_method($name, $value, $_REQUEST, __FUNCTION__);
    }

    /**
     * Env
     * 
     * @param string $name  = NULL
     * @param mixed  $value = NULL
     * 
     * @return mixed
     */
    public static function env(string $name = NULL, $value = NULL)
    {
        return self::_method($name, $value, $_ENV, __FUNCTION__);
    }

    /**
     * Server
     * 
     * @param string $name  = ''
     * @param mixed  $value = NULL
     * 
     * @return mixed
     */
    public static function server(string $name = '', $value = NULL)
    {
        // @value parametresi boş değilse
        if( ! empty($value) )
        {
            $_SERVER[$name] = $value;
        }

        return Server::data($name);
    }

    /**
     * Files
     * 
     * @param string $filename = NULL
     * @param string $type     = 'name'
     * 
     * @return mixed
     */
    public static function files(string $fileName = NULL, string $type = 'name')
    {
        return $_FILES[$fileName][$type] ?? false;
    }

    /**
     * Delete
     * 
     * @param string $input
     * @param string $name
     */
    public static function delete(string $input, string $name)
    {
        switch( $input )
        {
            case 'post'     : unset($_POST[$name]);    break;
            case 'get'      : unset($_GET[$name]);     break;
            case 'env'      : unset($_ENV[$name]);     break;
            case 'server'   : unset($_SERVER[$name]);  break;
            case 'request'  : unset($_REQUEST[$name]); break;
        }
    }

    /**
     * Protected Method
     */
    protected static function _method($name, $value, $input, $type)
    {
        if( empty($name) )
        {
            return $input;
        }

        # @value parametresi boş değilse 5.4.7[edited]
        if( $value !== NULL )
        {
            switch( $type )
            {
                case 'post'    : $_POST[$name]    = $value; break;
                case 'get'     : $_GET[$name]     = $value; break;
                case 'request' : $_REQUEST[$name] = $value; break;
                case 'env'     : $_ENV[$name]     = $value; break;
            }

            return true;
        }

        if( isset($input[$name]) )
        {
            if( is_scalar($input[$name]) )
            {
                if( Json::check($input[$name]) )
                {
                    return $input[$name];
                }
                else
                {
                    if( $type === 'get' || ($type === 'request' && isset($_GET[$name])) )
                    {
                        $input[$name] = In::cleanInjection($input[$name]);
                    }

                    return htmlspecialchars($input[$name], ENT_QUOTES, "utf-8");
                }
            }
            elseif( is_array($input[$name]) )
            {
                return array_map('ZN\Security\Html::encode', $input[$name]);
            }

            return $input[$name];
        }

        return false;
    }
}
