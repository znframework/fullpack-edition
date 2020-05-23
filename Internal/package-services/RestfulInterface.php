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
    public function getRequestHeaders() : Array;
    
    /**
     * Get raw data
     * 
     * @param string $type
     * 
     * @return string|array|object
     */
    public function getRawData(String $type = 'string');

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
    public function contentType(String $type = 'json', String $charset = 'utf-8') : Restful;

    /**
     * HTTP Status
     * 
     * @param int ¢ode = NULL
     * 
     * @return Restful
     */
    public function httpStatus(Int $code = NULL) : Restful;

    /**
     * Info
     * 
     * @param string $key = NULL
     * 
     * @return mixed
     */
    public function info(String $key = NULL);

    /**
     * URL
     * 
     * @param string 
     * 
     * @return Restful
     */
    public function url(String $url) : Restful;

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
    public function sslVerifyPeer(Bool $type = false) : Restful;

    /**
     * Get
     * 
     * @param string $url = NULL
     * 
     * @return object
     */
    public function get(String $url = NULL);

    /**
     * Post 
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function post(String $url = NULL, $data = NULL);

    /**
     * Post Json
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function postJson(String $url = NULL, $data = NULL);

    /**
     * Post Query
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function postQuery(String $url = NULL, $data = NULL);

    /**
     * Put 
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function put(String $url = NULL, $data = NULL);

    /**
     * Put Json
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function putJson(String $url = NULL, $data = NULL);

    /**
     * Patch 
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function patch(String $url = NULL, $data = NULL);

    /**
     * Patch Json
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function patchJson(String $url = NULL, $data = NULL);

    /**
     * Delete 
     * 
     * @param string $url  = NULL
     * @param mixed  $data = NULL
     * 
     * @return object
     */
    public function delete(String $url = NULL, $data = NULL);

    /**
     * Return
     * 
     * @param callable $callback
     * 
     * @return callable
     */
    public function return(Callable $callback);
}
