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

interface URLInterface
{
    /**
     * Get base name
     * 
     * @param string $uri = NULL
     * 
     * @return string
     */
    public static function base(string $uri = NULL) : string;

    /**
     * Get site URL
     * 
     * @param string $uri = NULL
     * 
     * @return string
     */
    public static function site(string $uri = NULL) : string;

    /**
     * Get site URLs
     * 
     * @param string $uri = NULL
     * 
     * @return string
     */
    public static function sites(string $uri = NULL) : string;

    /**
     * Get host name
     * 
     * @param string $uri = NULL
     * 
     * @return string
     */
    public static function host(string $uri = NULL) : string;

    /**
     * Get current URL
     * 
     * @param string $uri = NULL
     * 
     * @return string
     */
    public static function current(string $fix = NULL) : string;

    /**
     * Get prev URL
     * 
     * @return string
     */
    public static function prev() : string;

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
    public static function buildQuery($data, string $numericPrefix = NULL, string $separator = NULL, string $enctype = '+') : string;

    /**
     * Parse URL
     * 
     * @param string $url
     * @param mixed  $component = 1
     * 
     * @return mixed
     */
    public static function parse(string $url, $component = 1);
}
