<?php namespace ZN\Database\Postgres;
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
     * Modify Column
     * 
     * @param string $table
     * @param array  $column
     * 
     * @return string
     */
    public function modifyColumn($table, $column)
    {
        return 'ALTER TABLE '.$table.' ALTER COLUMN ' . $this->_syntax($column, 'TYPE') . ';';
    }

    /**
     * Rename Column
     * 
     * @param string $table
     * @param array  $column
     * 
     * @return string
     */
    public function renameColumn($table, $column)
    { 
        return 'ALTER TABLE '.$table.' RENAME COLUMN ' . $this->_syntax($column, 'TO') . ';';
    }

    /**
     * Add Column
     * 
     * @param string $table
     * @param array  $column
     * 
     * @return string
     */
    public function addColumn($table, $columns)
    {
        return 'ALTER TABLE ' . $table . ' ADD ' . $this->_extractColumn($columns) . ';';
    }
}