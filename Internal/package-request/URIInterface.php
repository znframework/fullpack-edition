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
     * Manipulation
     * 
     * @param array $rules
     * @param string $type = 'none' - options[none|left|right|both]
     * 
     * @return string
     */
    public static function manipulation(Array $rules, String $type = 'none') : String;

    /**
     * Build Query
     * 
     * @param array  $data
     * @param string $separator = '/'
     * @param string $type = 'none' - options[none|left|right|both]
     * 
     * @return string
     */
    public static function buildQuery(Array $data, String $separator = '/', String $type = 'none') : String;

    /**
     * Get
     * 
     * @param string|int $get   = 1
     * @param string|int $index = 1
     * @param bool       $while = false
     * 
     * @return string
     */
    public static function get($get = 1, $index = 1, Bool $while = false) : String;

    /**
     * Returns the number of segments after the specified segment.
     * 
     * @param string $get
     * 
     * @return int
     */
    public static function getNameCount(String $get) : Int;

    /**
     * Returns all segments after the specified segment.
     * 
     * @param string $get
     * 
     * @return string
     */
    public static function getNameAll(String $get) : String;

    /**
     * Used to get the range according to the index of the specified segment.
     * 
     * @param int $get   = 1
     * @param int $index = 1
     * 
     * @return string
     */
    public static function getByIndex(Int $get = 1, Int $index = 1) : String;

    /**
     * Used to get the range according to the specified segment names.
     * 
     * @param string $get
     * @param mixed  $index = NULL
     * 
     * @return string
     */
    public static function getByName(String $get, $index = NULL) : String;

    /**
     * Get Segment Array
     * 
     * @return array
     */
    public static function segmentArray() : Array;

    /**
     * Get Total Segments
     * 
     * @param int
     */
    public static function totalSegments() : Int;

    /**
     * Get Segment Count / Get Total Segments
     * 
     * @return int
     */
    public static function segmentCount() : Int;

    /**
     * Get segment
     * 
     * @param int $set = 1
     * 
     * @return string
     */
    public static function segment(Int $seg = 1) : String;

    /**
     * Current Segment
     * 
     * @return string
     */
    public static function currentSegment() : String;

    /**
     * Current
     * 
     * @param bool $isPath = true
     * 
     * @return string
     */
    public static function current(Bool $isPath = true) : String;

    /**
     * Get active URL
     * 
     * @param bool $fullPath = false
     * 
     * @return string
     */
    public static function active(Bool $fullPath = false) : String;

    /**
     * Base
     * 
     * @param string $uri = NULL
     * 
     * @return string
     */
    public static function base(String $uri = NULL) : String;

    /**
     * Prev
     * 
     * @param bool $isPath = true
     * 
     * @return string
     */
    public static function prev(Bool $isPath = true) : String;
}
