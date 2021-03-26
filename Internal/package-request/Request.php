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

use ZN\Helper;
use ZN\Request as Req;

class Request
{
    /**
     * Magic Call Static
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        if( $method === 'all' )
        {
            return Method::request();
        }

        return Method::request($method, $parameters[0] ?? NULL);
    }

    /**
     * IP v4
     * 
     * @param void
     * 
     * @return string
     */
    public static function ipv4() : string
    {
        return Req::ipv4();
    }

    /**
     * Scheme
     * 
     * @return string
     */
    public static function scheme() : string
    {
        return Server::data('requestScheme');
    }

    /**
     * Method
     * 
     * @param string $casing = 'upper'
     * 
     * @return string
     */
    public static function method(string $casing = 'upper') : string
    {
        return mb_convert_case(Server::data('requestMethod'), Helper::toConstant($casing, 'MB_CASE_'));
    }

    /**
     * URI
     * 
     * @return string
     */
    public static function uri() : string
    {
        return Server::data('requestUri');
    }

    /**
     * Time
     * 
     * @return int
     */
    public static function time() : int
    {
        return Server::data('requestTime');
    }

    /**
     * Time
     * 
     * @return float
     */
    public static function timeFloat() : Float
    {
        return Server::data('requestTimeFloat');
    }
}
