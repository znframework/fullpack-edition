<?php namespace ZN\Response;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface RedirectInterface
{
    /**
     * Get redirect status
     * 
     * @return int
     */
    public static function status() : int;

    /**
     * Get redirect url
     * 
     * @return string
     */
    public static function url() : string;

    /**
     * Get redirect string query
     * 
     * @return string
     */
    public static function queryString() : string;

    /**
     * Redirect code
     * 
     * @param int $code
     * 
     * @return Redirect
     */
    public function code(int $code) : Redirect;

    /**
     * Page refresh.
     * 
     * @param string $url  = NULL
     * @param int    $time = 0
     * @param array  $data = NULL
     * @param bool   $exit = false
     */
    public function refresh(string $url = NULL, int $time = 0, array $data = NULL, bool $exit = false);

    /**
     * Location
     *
     * @param string $url  = NULL
     * @param int    $time = 0
     * @param array  $data = NULL
     * @param bool   $exit = true
     */
    public function location
    (
        string $url  = NULL, 
        Int    $time = 0, 
        Array  $data = NULL, 
        Bool   $exit = true, 
               $type = 'location'
    );

    /**
     * Select redirect data
     * 
     * @param string $k
     * @param bool   $isDelete = false
     * 
     * @return false|mixed
     */
    public function selectData(string $k, bool $isDelete = false);

    /**
     * Redirect delete data
     * 
     * @param mixed $data
     * 
     * @return true
     */
    public function deleteData($data) : bool;

    /**
     * Action URL
     * 
     * @param string $action = NULL
     */
    public function action(string $action = NULL);

    /**
     * Sets redirect exit
     * 
     * @param bool $exit = 0
     * 
     * @return self
     */
    public function exit(bool $exit = true);

    /**
     * Sets redirect time
     * 
     * @param int $time = 0
     * 
     * @return self
     */
    public function time(int $time = 0);

    /**
     * Sets waiting time. same time() method
     * 
     * @param int $time = 0
     * 
     * @return self
     */
    public function wait(int $time = 0);

    /**
     * Sets redirect data
     * 
     * @param array $data
     * 
     * @return self
     */
    public function data(array $data);

    /**
     * Insert redirect data
     * 
     * @param array $data
     * 
     * @return self
     */
    public function insert(array $data);

    /**
     * Select redirect data
     * 
     * @param string $key
     * @param bool   $isDelete = false
     * 
     * @return mixed
     */
    public function select(string $key, bool $isDelete = false);

    /**
     * Deletes redirect data
     * 
     * @param mixed $key
     * 
     * @return true
     */
    public function delete($key) : bool;
}
