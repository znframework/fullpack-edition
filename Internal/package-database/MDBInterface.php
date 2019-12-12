<?php namespace ZN\Database;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

interface MDBInterface
{
   /**
     * Executable
     * 
     * @param array $options
     */
    public function executable($options);

    /**
     * MongoDB\Driver\WriteConcern
     * 
     * @return WriteConcern
     */
    public static function writeConcern(...$parameters);

    /**
     * MongoDB\Driver\ReadPreference
     * 
     * @return ReadPreference
     */
    public static function readPreference(...$parameters);

    /**
     * MongoDB\Driver\ReadConcern
     * 
     * @return ReadConcern
     */
    public static function readConcern(...$parameters);

    /**
     * MongoDB\Driver\Session
     * 
     * @return Session
     */
    public static function session(...$parameters);

    /**
     * New
     * 
     * @param array $config
     */
    public static function new(Array $config);

    /**
     * Insert
     * 
     * @param string $table
     * @param array  $datas
     * 
     * @return bool
     */
    public function insert(String $table, Array $datas) : Bool;

    /**
     * Delete
     * 
     * @param string $table
     * 
     * @return bool
     */
    public function delete(String $table) : Bool;

    /**
     * Update
     * 
     * @param string $table
     * @param array  $datas
     * 
     * @return bool
     */
    public function update(String $table, Array $datas = []) : Bool;

    /**
     * Where Regex
     * 
     * @param string $key
     * @param string $value
     * @param string $flags = ''
     * 
     * @return self
     */
    public function whereRegex(String $key, String $value, String $flags = '');
    
    /**
     * Where
     * 
     * @param string $key
     * @param string $value
     * 
     * @return self
     */
    public function where(String $key, String $value);

    /**
     * Upsert
     * 
     * @return self
     */
    public function upsert();

    /**
     * Option
     * 
     * @param string $key
     * @param scalar $value
     * 
     * @return self
     */
    public function option(String $key, $value);

    /**
     * Total Rows
     * 
     * @param int
     */
    public function totalRows() : Int;

    /**
     * Limit
     * 
     * Usage - 1
     * 
     * @param int $limit
     * 
     * Usage - 2
     * 
     * @param int $skip
     * @param int $limit
     * 
     * @return self
     */
    public function limit(Int $skip, Int $limit = NULL);

    /**
     * Order By
     * 
     * @param string $column
     * @param string $type = 'asc'
     * 
     * @return self
     */
    public function orderBy(String $column, String $type = 'asc');

    /**
     * Get
     * 
     * @param string $table
     * 
     * @return self
     */
    public function get(String $table);

    /**
     * Result
     * 
     * @return array|object
     */
    public function result();

    /**
     * Row
     * 
     * @param int|bool $printable = 0
     * 
     * @return object|false
     */
    public function row($printable = 0);

    /**
     * Create Index
     * 
     * @param string $table
     * @param array  $indexes
     * 
     * @return bool
     */
    public function createIndex(String $table, Array $indexes) : Bool;

    /**
     * Drop Index
     * 
     * @param string $table
     * @param string $indexName
     * 
     * @return bool
     */
    public function dropIndex(String $table, String $indexName) : Bool;

    /**
     * Drop/Truncate
     * 
     * @param string $table
     * 
     * @return bool
     */
    public function drop(String $table) : Bool;

    /**
     * Truncate/Drop
     * 
     * @param string $table
     * 
     * @return bool
     */
    public function truncate(String $table) : Bool;

    /**
     * Databases
     * 
     * @return array
     */
    public function databases() : Array;

    /**
     * Indexes
     * 
     * @return array
     */
    public function indexes(String $table) : Array;

    /**
     * Tables/Collections
     * 
     * @return array
     */
    public function tables() : Array;

    /**
     * Collections/Tables
     * 
     * @return array
     */
    public function collections() : Array;

    /**
     * Error
     * 
     * @return string
     */
    public function error() : String;
}
