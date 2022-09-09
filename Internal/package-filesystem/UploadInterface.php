<?php namespace ZN\Filesystem;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface UploadInterface
{
    /**
     * Uplaod progress
     * 
     * @param string   $selector
     * @param string   $source
     * @param callable $callable
     * 
     * @return string
     */
    public function progress(string $selector, string $source, callable $callable) : string;

    /**
     * Is file input name
     * 
     * @param string $name
     * 
     * @return bool
     */
    public function isFile(string $name) : bool;
    
    /**
     * Settings
     * 
     * @param array $settings = []
     * 
     * @return Upload
     */
    public function settings(array $settings = []) : Upload;

    /**
     * Sets extensions
     * 
     * @param string ...$args
     * 
     * @return Upload
     */
    public function extensions(...$args) : Upload;

    /**
     * Sets mimes
     * 
     * @param string ...$args
     * 
     * @return Upload
     */
    public function mimes(...$args) : Upload;

    /**
     * Sets convert name
     * 
     * @param string|bool $convert = true
     * 
     * @return Upload
     */
    public function convertName($convert = true) : Upload;

    /**
     * Defines encode type
     * 
     * @param string $hash = 'md5'
     * 
     * @return Upload
     */
    public function encode(string $hash = 'md5') : Upload;

    /**
     * Sets prefix
     * 
     * @param string $prefix
     * 
     * @return Upload
     */
    public function prefix(string $prefix) : Upload;

    /**
     * Sets maxsize
     * 
     * @param string|int $maxsize = 0
     * 
     * @return Upload
     */
    public function maxsize($maxsize = 0) : Upload;

    /**
     * Sets encode length
     * 
     * @param int $encodeLength = 8
     * 
     * @return Upload
     */
    public function encodeLength(int $encodeLength = 8) : Upload;

    /**
     * Sets target
     * 
     * @param string $target
     * 
     * @return Upload
     */
    public function target(string $target) : Upload;

    /**
     * Sets source
     * 
     * @param string $source = 'upload'
     * 
     * @return Upload
     */
    public function source(string $source = 'upload') : Upload;

    /**
     * Start file upload
     * 
     * @param string $fileName = 'upload'
     * @param string $rootDir  = NULL
     * 
     * @return bool
     */
    public  function start(string $fileName = 'upload', string $rootDir = NULL) : bool;

    /**
     * Gets info
     * 
     * @param string $info = NULL
     * 
     * @return object|false
     */
    public function info(string $info = NULL);

    /**
     * Gets error
     * 
     * @return string|false
     */
    public function error();

    /**
     * Clean
     */
    public function clean();
}
