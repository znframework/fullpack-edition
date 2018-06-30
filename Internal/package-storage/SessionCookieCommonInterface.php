<?php namespace ZN\Storage;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface SessionCookieCommonInterface
{
    /**
     * Encode session key & value
     * 
     * @param string $nameAlgo  = NULL
     * @param string $valueAlgo = NULL
     * 
     * @return $this
     */
    public function encode(String $name, String $value);
    
    /**
     * Decode only session key
     * 
     * @param string $nameAlgo
     * 
     * @return $this
     */
    public function decode(String $hash);
    
    /**
     * Regenerate status
     * 
     * @param bool $regenerate = true
     * 
     * @return $this
     */
    public function regenerate(Bool $regenerate);

    /**
     * Insert session
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return bool
     */
    public function insert(String $name, $value) : Bool;

    /**
     * Select session
     * 
     * @param string $name
     * 
     * @return mixed
     */
    public function select(String $name);

    /**
     * Delete session
     * 
     * @param string $name
     * 
     * @return bool
     */
    public function delete(String $name) : Bool;

    /**
     * Select all session
     * 
     * @param void
     * 
     * @return array
     */
    public function selectAll() : Array;

    /**
     * Delete all session
     * 
     * @param void
     * 
     * @return void
     */
    public function deleteAll() : Bool;

    /**
     * Session start
     * 
     * @param void
     * 
     * @return void
     */
    public static function start();
}