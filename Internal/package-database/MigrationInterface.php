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

interface MigrationInterface
{
    /**
     * Up all migrations
     * 
     * @param string ...$migrations
     * 
     * @return bool
     */
    public function upAll(string ...$migrations) : bool;

    /**
     * Down all migrations
     * 
     * @param string ...$migrations
     * 
     * @return bool
     */
    public function downAll(string ...$migrations) : bool;

    /**
     * Create table
     * 
     * @param array $data
     * 
     * @return bool
     */
    public function createTable(array $data) : bool;

    /**
     * Drop table
     * 
     * @param void
     * 
     * @return bool
     */
    public function dropTable() : bool;

    /**
     * Add column
     * 
     * @param array $column
     * 
     * @return bool
     */
    public function addColumn(array $columns) : bool;

    /**
     * Drop column
     * 
     * @param mixed $column
     * 
     * @return bool
     */
    public function dropColumn($columns) : bool;

    /**
     * Modify column
     * 
     * @param array $column
     * 
     * @param bool
     */
    public function modifyColumn(array $columns) : bool;

    /**
     * Rename column
     * 
     * @param array $column
     * 
     * @return bool
     */
    public function renameColumn(array $column) : bool;

    /**
     * Truncate table
     * 
     * @param void
     * 
     * @return bool
     */
    public function truncate() : bool;

    /**
     * Sets migration path
     * 
     * @param string $path = NULL
     * 
     * @return Migration
     */
    public function path(string $path) : Migration;

    /**
     * Selects migration version
     * 
     * @param int $version = 0
     * 
     * @return object
     */
    public function version(int $version = 0);

    /**
     * Create migration
     * 
     * @param string $name
     * @param int    $version = 0
     * 
     * @return bool
     */
    public function create(string $name, int $ver = 0) : bool;

    /**
     * Delete migration
     * 
     * @param string $name
     * @param int    $version = 0
     * 
     * @return bool
     */
    public function delete(string $name, int $ver = 0) : bool;

    /**
     * Delete all migrations
     * 
     * @param void
     * 
     * @return bool
     */
    public function deleteAll() : bool;
}
