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

use stdClass;

class Content
{
    /**
     * Read File
     * 
     * @param string $file
     * 
     * @return string
     */
    public static function read(string $file) : string
    {
        return file_get_contents(Info::rpath($file));
    }

    /**
     * Find Data
     * 
     * @param string $file
     * @param string $data
     * 
     * @return object
     */
    public static function find(string $file, string $data) : stdClass
    {
        $contents = self::read($file);
        $index    = strpos($contents, $data);

        return (object)
        [
            'index'    => $index,
            'contents' => $contents
        ];
    }

    /**
     * Write Data
     * 
     * @param string $file
     * @param string $data
     * 
     * @return int
     */
    public static function write(string $file, string $data) : int
    {
        return file_put_contents(Info::rpath($file), $data);
    }

    /**
     * Append Data
     * 
     * @param string $file
     * @param string $data
     * 
     * @return int
     */
    public static function append(string $file, string $data) : int
    {
        return file_put_contents(Info::rpath($file), $data, FILE_APPEND);
    }
}
