<?php namespace ZN\Compression;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface ForceInterface
{
    /**
     * Extract data
     * 
     * @param string $source
     * @param string $target   = NULL
     * @param string $password = NULL
     * 
     * @return bool
     */
    public function extract(string $source, string $target = NULL, string $password = NULL) : bool;

    /**
     * Write data to file
     * 
     * @param string $file
     * @param string $data
     * 
     * @return bool
     */
    public function write(string $file, string $data) : bool;

    /**
     * Read file
     * 
     * @param string $file
     * 
     * @return bool
     */
    public function read(string $file) : string;

    /**
     * Force do
     * 
     * @param string $data
     * 
     * @return string
     */
    public function do(string $data) : string;

    /**
     * Force undo
     * 
     * @param string $data
     * 
     * @return string
     */
    public function undo(string $data) : string;
}
