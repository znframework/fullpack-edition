<?php namespace ZN\Database\Oracle;
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
     * Create Temporary Table
     * 
     * @param string $tabşe
     * @param array  $columns
     * @param string $extras
     * 
     * @return string
     */
    public function createTempTable($table, $columns, $extras)
    {
        return 'CREATE GLOBAL TEMPORARY TABLE ' . $this->createTableColumnsSyntax($table, $columns, $extras) .  ' ON COMMIT PRESERVE ROWS;';
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
    public function dropIndex($indexName, $table = NULL)
    {
        return 'DROP INDEX ' . $indexName . ';';
    }
}