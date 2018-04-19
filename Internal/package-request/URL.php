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

use ZN\Base;
use ZN\Helper;
use ZN\Request;

class URL implements URLInterface
{
    /**
     * Get base name
     * 
     * @param string $uri = NULL
     * 
     * @return string
     */
    public static function base(String $uri = NULL) : String
    {
        return Request::getBaseURL($uri);
    }

    /**
     * Get site URL
     * 
     * @param string $uri = NULL
     * 
     * @return string
     */
    public static function site(String $uri = NULL) : String
    {
        return Request::getSiteURL($uri);
    }

    /**
     * Get site URLs
     * 
     * @param string $uri = NULL
     * 
     * @return string
     */
    public static function sites(String $uri = NULL) : String
    {
        return str_replace(SSL_STATUS, Http::fix(true), self::site($uri));
    }

    /**
     * Get host name
     * 
     * @param string $uri = NULL
     * 
     * @return string
     */
    public static function host(String $uri = NULL) : String
    {
        return Request::getHostName($uri);
    }

    /**
     * Get current URL
     * 
     * @param string $uri = NULL
     * 
     * @return string
     */
    public static function current(String $fix = NULL) : String
    {
        $currentUrl = Request::getHostName(Server::data('requestUri'));

        if( ! empty($fix) )
        {
            return Base::suffix(rtrim($currentUrl, $fix)) . $fix;
        }

        return $currentUrl;
    }

    /**
     * Get prev URL
     * 
     * @return string
     */
    public static function prev() : String
    {
        return $_SERVER['HTTP_REFERER'] ?? '';
    }

    /**
     * Build Query
     * 
     * @param mixed $data
     * @param string $numericPrefix = NULL
     * @param string $separator     = NULL
     * @param int    $enctype       = PHP_QUERY_RFC1738
     * 
     * @return mixed
     */
    public static function buildQuery($data, String $numericPrefix = NULL, String $separator = NULL, Int $enctype = PHP_QUERY_RFC1738) : String
    {
        return http_build_query($data, $numericPrefix, $separator ?? '&', $enctype);
    }

    /**
     * Parse URL
     * 
     * @param string $url
     * @param mixed  $component = 1
     * 
     * @return mixed
     */
    public static function parse(String $url, $component = 1)
    {
        return parse_url($url, Helper::toConstant($component, 'PHP_URL_'));
    }
}
