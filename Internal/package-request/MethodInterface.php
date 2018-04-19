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

interface MethodInterface
{
    /**
     * Post
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return mixed
     */
    public static function post(String $name, $value);

    /**
     * Get
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return mixed
     */
    public static function get(String $name, $value);

    /**
     * Env
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return mixed
     */
    public static function env(String $name, $value);

    /**
     * Server
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return mixed
     */
    public static function server(String $name, $value);

    /**
     * Request
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return mixed
     */
    public static function request(String $name, $value);

    /**
     * Files
     * 
     * @param string $fileName 
     * @param string $type
     * 
     * @return mixed
     */
    public static function files(String $fileName, String $type);

    /**
     * Delete
     * 
     * @param string $input 
     * @param string $name
     * 
     * @return mixed
     */
    public static function delete(String $input, String $name);
}
