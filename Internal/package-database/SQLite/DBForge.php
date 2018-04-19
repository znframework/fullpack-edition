<?php namespace ZN\Database\SQLite;
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
     * Truncate
     * 
     * @param string $table
     * 
     * @return string
     */
    public function truncate($table)
    { 
        return 'DELETE FROM '.$table; 
    }

    /**
     * Unsupported
     */
    public function dropColumn($table, $column)
    {
        return false;
    }

    /**
     * Unsupported
     */
    public function modifyColumn($table, $column)
    {
        return false;
    }

    /**
     * Unsupported
     */
    public function renameColumn($table, $column)
    { 
        return false;
    }

    /**
     * Add column
     *
     * @param string $table
     * @param array  $columns
     * 
     * @return string
     */
    public function addColumn($table, $columns)
    {
        return 'ALTER TABLE ' . $table . ' ADD ' . $this->_extractColumn($columns) . ';';
    }
}