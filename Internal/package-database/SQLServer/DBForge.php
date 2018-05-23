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
        return 'ALTER TABLE '.$table.' RENAME COLUMN '.key($column).' TO '.current($column).';';
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
        return 'DROP INDEX ' . $indexName . '.' . $table . ';';
    }
}