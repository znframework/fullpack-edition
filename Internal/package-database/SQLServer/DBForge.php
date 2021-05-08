<?php namespace ZN\Database\SQLServer;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Database\DriverForge;

class DBForge extends DriverForge
{
    /**
     * Unsupported
     */
    public function extras($extras)
    {
        return NULL;
    }
    
    /**
     * Rename column
     * 
     * @param string $table
     * @param array  $column
     * 
     * @return string
     */
    public function renameColumn($table, $column)
    { 
        return "sp_rename '$table." . key($column) . "', '" . current($column) . "', 'COLUMN';";
    }

    /**
     * Drop index
     *
     * 5.7.4[added]
     * 
     * @param string $indexName
     * @param string $table
     * 
     * @return string
     */
    public function dropIndex($indexName, $table)
    {
        return 'DROP INDEX ' . $table . '.' . $indexName . ';';
    }

    /**
     * Rename Table
     * 
     * @param string $name
     * @param string $newname
     * 1
     * @return string
     */
    public function renameTable($name, $newName)
    {
        return "sp_rename '$name', '$newName';";
    }

    /**
     * Add Column
     * 
     * @param string $table
     * @param array  $columns
     * 
     * @return string
     */
    public function addColumn($table, $columns)
    {
        return 'ALTER TABLE ' . $table . ' ADD ' . $this->buildForgeColumnsQuery($columns) . ';';
    }

    /**
     * MOdify Column
     * 
     * @param string $table
     * @param array  $columns
     * 
     * @return string
     */
    public function modifyColumn($table, $columns)
    {
        return 'ALTER TABLE ' . $table . ' ALTER COLUMN ' . $this->buildForgeColumnsQuery($columns) . ';';
    }

    /**
     * Drop Column
     * 
     * @param string $table
     * @param array  $columns
     * 
     * @return string
     */
    public function dropColumn($table, $column)
    {
        return 'ALTER TABLE ' . $table . ' DROP COLUMN ' . $column . ';';
    }

    /**
     * Create index
     *
     * 5.7.4[added]
     * 
     * @param string $indexName
     * @param string $table
     * @param string $columns
     * 
     * @return string
     */
    public function createFulltextIndex($indexName, $table, $columns)
    {
        return false;
    }
}