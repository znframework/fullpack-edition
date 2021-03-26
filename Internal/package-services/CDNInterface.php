<?php namespace ZN\Services;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface CDNInterface
{
    /**
     * Get Links
     * 
     * @return array
     */
    public function links() : array;

    /**
     * Get link
     * 
     * @param string $key
     * @param string $version = 'latest'
     * 
     * @return string|false
     */
    public function link(string $key, string $version = 'latest');

    /**
     * Refresh request api.
     * 
     * @return $this
     */
    public function refresh() : CDN;

    /**
     * Set json file path.
     * 
     * @param string $jsonFile
     * 
     * @return $this
     */
    public function setJsonFile(string $jsonFile) : CDN;

    /**
     * Get cdn data.
     * 
     * @param string $configName
     * @param string $name
     * 
     * @return string
     */
    public static function get(string $configName, string $name) : string;

    /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function image(string $name) : string;

    /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function style(string $name) : string;

   /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function script(string $name) : string;

    /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function font(string $name) : string;

    /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function file(string $name) : string;
}
