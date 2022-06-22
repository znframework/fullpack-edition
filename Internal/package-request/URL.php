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
use ZN\Ability\Singleton;

class URL implements URLInterface
{
    use Singleton;

    /**
     * Sets lang
     * 
     * @param string $lang
     * 
     * @return self
     */
    public static function lang(string $lang)
    {
        Request::$lang = $lang;

        return self::singleton();
    }

    /**
     * Get base name
     * 
     * @param string $uri = NULL
     * 
     * @return string
     */
    public static function base(string $uri = NULL) : string
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
    public static function site(string $uri = NULL) : string
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
    public static function sites(string $uri = NULL) : string
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
    public static function host(string $uri = NULL) : string
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
    public static function current(string $fix = NULL) : string
    {
        $currentUrl = Request::getHostName(Server::data('requestUri'));

        if( ! empty($fix) )
        {
            # It allows the parametric value to be inserted after the / symbol.
            # If the parametric value is present, it is not appended to the end of the URI.
            return Base::suffix(Base::removeSuffix($currentUrl, $fix)) . $fix;
        }

        return $currentUrl;
    }

    /**
     * Get prev URL
     * 
     * @return string
     */
    public static function prev() : string
    {
        return $_SERVER['HTTP_REFERER'] ?? '';
    }

    /**
     * Build Query
     * 
     * @param mixed  $data
     * @param string $numericPrefix = NULL
     * @param string $separator     = NULL
     * @param string $type          = '+' - options[+|%]
     * 
     * @return mixed
     */
    public static function buildQuery($data, string $numericPrefix = NULL, string $separator = NULL, string $enctype = '+') : string
    {
        $rfc = $enctype === '+' ? PHP_QUERY_RFC1738 : PHP_QUERY_RFC3986;

        return http_build_query($data, $numericPrefix ?? '', $separator ?? '&', $rfc);
    }

    /**
     * Parse URL
     * 
     * @param string $url
     * @param mixed  $component = 1
     * 
     * @return mixed
     */
    public static function parse(string $url, $component = 1)
    {
        return parse_url($url, Helper::toConstant($component, 'PHP_URL_'));
    }
}
