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
    public static function new(array $config);

    /**
     * Insert
     * 
     * @param string $table
     * @param array  $datas
     * 
     * @return bool
     */
    public function insert(string $table, array $datas) : bool;

    /**
     * Delete
     * 
     * @param string $table
     * 
     * @return bool
     */
    public function delete(string $table) : bool;

    /**
     * Update
     * 
     * @param string $table
     * @param array  $datas
     * 
     * @return bool
     */
    public function update(string $table, array $datas = []) : bool;

    /**
     * Where Regex
     * 
     * @param string $key
     * @param scalar $value
     * @param string $flags = ''
     * 
     * @return self
     */
    public function whereRegex(string $key, $value, string $flags = '');
    
    /**
     * Where
     * 
     * @param string $key
     * @param scalar $value
     * 
     * @return self
     */
    public function where(string $key, $value);

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
    public function option(string $key, $value);

    /**
     * Filter
     * 
     * @param string $key
     * @param scalar $value
     * 
     * @return self
     */
    public function filter(string $key, $value);

    /**
     * Total Rows
     * 
     * @param int
     */
    public function totalRows() : int;

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
    public function limit(int $skip, int $limit = NULL);

    /**
     * Order By
     * 
     * @param string $column
     * @param string $type = 'asc'
     * 
     * @return self
     */
    public function orderBy(string $column, string $type = 'asc');

    /**
     * Get
     * 
     * @param string $table
     * 
     * @return self
     */
    public function get(string $table);

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
    public function createIndex(string $table, array $indexes) : bool;

    /**
     * Drop Index
     * 
     * @param string $table
     * @param string $indexName
     * 
     * @return bool
     */
    public function dropIndex(string $table, string $indexName) : bool;

    /**
     * Create
     * 
     * @param string $table
     * @param array  $options = []
     * 
     * @return bool
     */
    public function create(string $table, array $options = []) : bool;

    /**
     * Create Auto Increment
     * 
     * @param string $table
     * @param string $column
     */
    public function createAutoIncrement(string $table, string $column);

    /**
     * Drop/Truncate
     * 
     * @param string $table
     * 
     * @return bool
     */
    public function drop(string $table) : bool;

    /**
     * Truncate/Drop
     * 
     * @param string $table
     * 
     * @return bool
     */
    public function truncate(string $table) : bool;

    /**
     * Databases
     * 
     * @return array
     */
    public function databases() : array;

    /**
     * Indexes
     * 
     * @return array
     */
    public function indexes(string $table) : array;

    /**
     * Tables/Collections
     * 
     * @return array
     */
    public function tables() : array;

    /**
     * Collections/Tables
     * 
     * @return array
     */
    public function collections() : array;

    /**
     * Error
     * 
     * @return string
     */
    public function error() : string;
}
