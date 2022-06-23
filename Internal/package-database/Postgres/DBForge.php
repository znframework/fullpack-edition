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
     * Unsupported
     */
    public function extras($extras)
    {
        return NULL;
    }

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
        $col = key($column); $values = (array) current($column); $query = '';
        
        foreach( $values as $value )
        {
            $type = preg_match('/(NULL|DEFAULT|CONSTRAINT|EXISTS|UNIQUE|KEY|BIGSERIAL)/i', $value ?? '') ? 'SET' : 'TYPE';

            $query .= 'ALTER TABLE ' . $table . ' ALTER COLUMN ' . $this->buildForgeColumnsSyntax([$col => [$value]], $type) . ';';
        }

        return $query;
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
        return 'ALTER TABLE '.$table.' RENAME COLUMN ' . $this->buildForgeColumnsSyntax($column, 'TO') . ';';
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
        return 'ALTER TABLE ' . $table . ' ADD ' . $this->buildForgeColumnsQuery($columns) . ';';
    }

    /**
     * Drop index
     * 
     * @param string $indexName
     * @param string $table
     * 
     * @return string
     */
    public function dropIndex($indexName, $table)
    {
        return 'DROP INDEX ' . $indexName . ';';
    }
}