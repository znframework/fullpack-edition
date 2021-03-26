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
    public static function post(string $name, $value);

    /**
     * Get
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return mixed
     */
    public static function get(string $name, $value);

    /**
     * Env
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return mixed
     */
    public static function env(string $name, $value);

    /**
     * Server
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return mixed
     */
    public static function server(string $name, $value);

    /**
     * Request
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return mixed
     */
    public static function request(string $name, $value);

    /**
     * Files
     * 
     * @param string $fileName 
     * @param string $type
     * 
     * @return mixed
     */
    public static function files(string $fileName, string $type);

    /**
     * Delete
     * 
     * @param string $input 
     * @param string $name
     * 
     * @return mixed
     */
    public static function delete(string $input, string $name);
}
