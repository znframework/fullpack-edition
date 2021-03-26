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

interface RestfulInterface
{
    /**
     * Get request headers
     * 
     * @return array
     */
    public function getRequestHeaders() : array;
    
    /**
     * Get raw data
     * 
     * @param string $type
     * 
     * @return string|array|object
     */
    public function getRawData(string $type = 'string');

    /**
     * Get raw data object
     * 
     * @return object
     */
    public function getRawDataObject();

    /**
     * Get raw data array
     * 
     * @return array
     */
    public function getRawDataArray();

    /**
     * Content Type
     * 
     * @param string $type    = 'json'
     * @param string $charset = 'utf-8'
     * 
     * @return Restful
     */
    public function contentType(string $type = 'json', string $charset = 'utf-8') : Restful;

    /**
     * HTTP Status
     * 
     * @param int ¢ode = NULL
     * 
     * @return Restful
     */
    public function httpStatus(int $code = NULL) : Restful;

    /**
     * Info
     * 
     * @param string $key = NULL
     * 
     * @return mixed
     */
    public function info(string $key = NULL);

    /**
     * URL
     * 
     * @param string 
     * 
     * @return Restful
     */
    public function url(string $url) : Restful;

    /**
     * Data
     * 
     * @param mixed $data
     * 
     * @return Restful
     */
    public function data($data) : Restful;

    /**
     * SSL Verify Peer
     * 
     * @param bool $type = false
     * 
     * @return Restful
     */
    public function sslVerifyPeer(bool $type = false) : Restful;

    /**
     * Get
     * 
     * @param string $url = NULL
     * 
     * @return object
     */
    public function get(string $url = NULL);

    /**
     * Post 
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function post(string $url = NULL, $data = NULL);

    /**
     * Post Json
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function postJson(string $url = NULL, $data = NULL);

    /**
     * Put 
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function put(string $url = NULL, $data = NULL);

    /**
     * Put Json
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function putJson(string $url = NULL, $data = NULL);

    /**
     * Patch 
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function patch(string $url = NULL, $data = NULL);

    /**
     * Patch Json
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function patchJson(string $url = NULL, $data = NULL);

    /**
     * Delete 
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function delete(string $url = NULL, $data = NULL);

    /**
     * Return
     * 
     * @param callable $callback
     * 
     * @return callable
     */
    public function return(callable $callback);
}
