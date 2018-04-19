<?php namespace ZN\Database\ODBC;
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
     * Truncate table
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
     * Rename column
     * 
     * @param string $table
     * @param string $table
     * 
     * @return string
     */
    public function renameColumn($table, $column)
    { 
        return 'ALTER TABLE '.$table.' RENAME COLUMN  '.rtrim($column, ',').';';
    }
}