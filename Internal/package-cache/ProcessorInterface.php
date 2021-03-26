<?php namespace ZN\Cache;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface ProcessorInterface
{
    /**
     * Refresh cache
     * 
     * @param void
     * 
     * @return Cache
     */
    public function refresh();

    /**
     * Set data
     * 
     * @param array $data = NULL
     * 
     * @return Cache
     */
    public function data(array $data = NULL);

    /**
     * Set key
     * 
     * @param string $key = NULL
     * 
     * @return Cache
     */
    public function key(string $key = NULL) : Processor;

    /**
     * Cache code
     * 
     * @param callable $function
     * @param mixed    $time       = 60
     * @param string   $compressed = 'gz'
     * 
     * @return string
     */
    public function code(callable $function, $time = 60, string $compress = 'gz') : string;

    /**
     * Cache view
     * 
     * @param string $file
     * @param mixed  $time     = 60
     * @param string $compress = 'gz'
     * 
     * @return string
     */
    public function view(string $file, $time = 60, string $compress = 'gz') : string;

    /**
     * Cache file
     * 
     * @param string $file
     * @param mixed  $time     = 60
     * @param string $compress = 'gz'
     * 
     * @return string
     */
    public function file(string $file, $time = 60, string $compress = 'gz', $type = 'something') : string;

    /**
     * Select key
     * 
     * @param string $key
     * @param mixed  $compressed = false
     * 
     * @return mixed
     */
    public function select(string $key, $compressed = false);

    /**
     * Insert key
     * 
     * @param string $key
     * @param mixed  $var
     * @param mixed  $time       = 60
     * @param mixed  $compressed = false
     * 
     * @return bool
     */
    public function insert(string $key, $var, $time = 60, $compressed = false) : bool;

    /**
     * Delete key
     * 
     * @param string $key
     * 
     * @return bool
     */
    public function delete(string $key) : bool;

    /**
     * Increment key
     * 
     * @param string $key
     * @param int    $increment = 1
     * 
     * @return int
     */
    public function increment(string $key, int $increment = 1) : int;

    /**
     * Decrement key
     * 
     * @param string $key
     * @param int    $decrement = 1
     * 
     * @return int
     */
    public function decrement(string $key, int $decrement = 1) : int;

    /**
     * Clean all cache
     * 
     * @param void
     * 
     * @return bool
     */
    public function clean() : bool;

    /**
     * Get info
     * 
     * @param mixed $type
     * 
     * @return array
     */
    public function info($info) : array;
}
