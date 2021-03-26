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

interface URIInterface
{
    /**
     * Data usage.
     * 
     * @param string|array $data
     * 
     * @return self
     */
    public static function data($data) : URI;

    /**
     * Manipulation
     * 
     * @param array $rules
     * @param string $type = 'none' - options[none|left|right|both]
     * 
     * @return string
     */
    public static function manipulation(array $rules, string $type = 'none') : string;

    /**
     * Build Query
     * 
     * @param array  $data
     * @param string $separator = '/'
     * @param string $type = 'none' - options[none|left|right|both]
     * 
     * @return string
     */
    public static function buildQuery(array $data, string $separator = '/', string $type = 'none') : string;

    /**
     * Get
     * 
     * @param string|int $get   = 1
     * @param string|int $index = 1
     * @param bool       $while = false
     * 
     * @return string
     */
    public static function get($get = 1, $index = 1, bool $while = false) : string;

    /**
     * Returns the number of segments after the specified segment.
     * 
     * @param string $get
     * 
     * @return int
     */
    public static function getNameCount(string $get) : int;

    /**
     * Returns all segments after the specified segment.
     * 
     * @param string $get
     * 
     * @return string
     */
    public static function getNameAll(string $get) : string;

    /**
     * Used to get the range according to the index of the specified segment.
     * 
     * @param int $get   = 1
     * @param int $index = 1
     * 
     * @return string
     */
    public static function getByIndex(int $get = 1, int $index = 1) : string;

    /**
     * Used to get the range according to the specified segment names.
     * 
     * @param string $get
     * @param mixed  $index = NULL
     * 
     * @return string
     */
    public static function getByName(string $get, $index = NULL) : string;

    /**
     * Get Segment Array
     * 
     * @return array
     */
    public static function segmentArray() : array;

    /**
     * Get Total Segments
     * 
     * @param int
     */
    public static function totalSegments() : int;

    /**
     * Get Segment Count / Get Total Segments
     * 
     * @return int
     */
    public static function segmentCount() : int;

    /**
     * Get segment
     * 
     * @param int $set = 1
     * 
     * @return string
     */
    public static function segment(int $seg = 1) : string;

    /**
     * Current Segment
     * 
     * @return string
     */
    public static function currentSegment() : string;

    /**
     * Current
     * 
     * @param bool $isPath = true
     * 
     * @return string
     */
    public static function current(bool $isPath = true) : string;

    /**
     * Get active URL
     * 
     * @param bool $fullPath = false
     * 
     * @return string
     */
    public static function active(bool $fullPath = false) : string;

    /**
     * Base
     * 
     * @param string $uri = NULL
     * 
     * @return string
     */
    public static function base(string $uri = NULL) : string;

    /**
     * Prev
     * 
     * @param bool $isPath = true
     * 
     * @return string
     */
    public static function prev(bool $isPath = true) : string;
}
