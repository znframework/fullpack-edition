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
    public function links() : Array;

    /**
     * Get link
     * 
     * @param string $key
     * @param string $version = 'latest'
     * 
     * @return string|false
     */
    public function link(String $key, String $version = 'latest');

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
    public function setJsonFile(String $jsonFile) : CDN;

    /**
     * Get cdn data.
     * 
     * @param string $configName
     * @param string $name
     * 
     * @return string
     */
    public static function get(String $configName, String $name) : String;

    /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function image(String $name) : String;

    /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function style(String $name) : String;

   /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function script(String $name) : String;

    /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function font(String $name) : String;

    /**
     * Get value.
     * 
     * @param string $name
     * 
     * @return string
     */
    public static function file(String $name) : String;
}
