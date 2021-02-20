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

use ZN\Singleton;

class DriverForge
{
    /**
     * Create Table & Database Extras
     * 
     * @param mixed $extras
     * 
     * @return mixed
     * 
     * @codeCoverageIgnore
     */
    public function extras($extras)
    {
        return $extras;
    }

    /**
     * Create Database
     * 
     * @param string $dbname
     * @param string $extras
     * 
     * @return string
     */
    public function createDatabase($dbname, $extras)
    {
        return 'CREATE DATABASE ' . $dbname . $this->_extras($extras);
    }

    /**
     * Drop Database
     * 
     * @param string $dbname
     * 
     * @return string
     */
    public function dropDatabase($dbname)
    {
        return 'DROP DATABASE ' . $dbname;
    }

    /**
     * Create Table
     * 
     * @param string $tabşe
     * @param array  $columns
     * @param string $extras
     * 
     * @return string
     */
    public function createTable($table, $columns, $extras)
    {
        return 'CREATE TABLE ' . $this->createTableColumnsSyntax($table, $columns, $extras);
    }

    /**
     * Create Temporary Table
     * 
     * @param string $tabşe
     * @param array  $columns
     * @param string $extras
     * 
     * @return string
     * 
     * @codeCoverageIgnore
     */
    public function createTempTable($table, $columns, $extras)
    {
        return 'CREATE TEMPORARY TABLE ' . $this->createTableColumnsSyntax($table, $columns, $extras);
    }

    /**
     * Protected create table columns syntax
     */
    protected function createTableColumnsSyntax($table, $columns, $extras)
    {
        $column = '';

        foreach( $columns as $key => $value )
        {
            $values = '';

            if( is_array($value) ) foreach( $value as $val )
            {
                $values .= ' ' . rtrim($val);
            }
            else
            {
                $values = $value;
            }

            $this->commonConversion($key, $values);

            $column .= $key . ' ' . rtrim($values) . ', ';
        }

        return  $table . '(' .rtrim(trim($column), ', ') . ')' . $this->_extras($extras);
    }

    /**
     * Drop Table
     * 
     * @param string $table
     * 
     * @return string
     */
    public function dropTable($table)
    {
        return 'DROP TABLE ' . $table;
    }

    /**
     * Alter Table
     * 
     * @param string $table
     * @param mixed  $condition
     */
    public function alterTable($table, $condition){}

    /**
     * Rename Table
     * 
     * @param string $name
     * @param string $newname
     * 
     * @return string
     */
    public function renameTable($name, $newName)
    {
        return 'ALTER TABLE ' . $name . ' RENAME TO ' . $newName;
    }

    /**
     * Truncate
     * 
     * @param string $table
     * 
     * @return string
     */
    public function truncate($table)
    {
        return 'TRUNCATE TABLE ' . $table;
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
        return 'ALTER TABLE ' . $table . ' ADD (' . $this->_extractColumn($columns) . ');';
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
        return 'ALTER TABLE ' . $table . ' DROP ' . $column . ';';
    }

    /**
     * Start Auto Increment
     *
     * 5.7.4[added]
     * 
     * @param string $table
     * @param int    $start = 0
     * 
     * @return string
     * 
     * @codeCoverageIgnore
     */
    public function startAutoIncrement($table, $start = 0)
    {
        return 'ALTER TABLE ' . $table . ' ' . $this->db()->autoIncrement() . '=' . $start . ';';
    }

    /**
     * Add Primary Key
     *
     * 5.7.4[added]
     * 
     * @param string $table
     * @param string $columns
     * @param string $constraint = NULL
     * 
     * @return string
     */
    public function addPrimaryKey($table, $columns, $constraint = NULL)
    {
        return 'ALTER TABLE ' . $table . ' ADD ' . $this->addedConstraint($constraint) . $this->db()->primaryKey() . '(' . $columns . ');';
    }

    /**
     * Add Foreign Key
     *
     * 5.7.4[added]
     * 
     * @param string $table
     * @param string $columns
     * @param string $reftable
     * @param string $refcolumn
     * @param string $constraint = NULL
     * 
     * @return string
     */
    public function addForeignKey($table, $columns, $reftable, $refcolumn, $constraint = NULL)
    {
        return 'ALTER TABLE ' . $table . ' ADD ' . $this->addedConstraint($constraint) . $this->db()->foreignKey() . '(' . $columns . ') REFERENCES '.$reftable.'('.$refcolumn.');';
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
    public function createIndex($indexName, $table, $columns, $uniq = NULL)
    {
        return 'CREATE ' . $uniq . ' INDEX ' . $indexName . ' ON ' . $table . ' (' . $columns . ');';
    }

    /**
     * Create Unique index
     *
     * 5.7.4[added]
     * 
     * @param string $indexName
     * @param string $table
     * @param string $columns
     * 
     * @return string
     */
    public function createUniqueIndex($indexName, $table, $columns)
    {
        return $this->createIndex($indexName, $table, $columns, 'UNIQUE');
    }

    /**
     * Create Fulltext index
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
        return $this->createIndex($indexName, $table, $columns, 'FULLTEXT');
    }

    /**
     * Create Spatial index
     *
     * 5.7.4[added]
     * 
     * @param string $indexName
     * @param string $table
     * @param string $columns
     * 
     * @return string
     */
    public function createSpatialIndex($indexName, $table, $columns)
    {
        return $this->createIndex($indexName, $table, $columns, 'SPATIAL');
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
        return 'ALTER TABLE ' . $table . ' DROP INDEX ' . $indexName . ';';
    }

    /**
     * Drop Primary Key
     * 
     * 5.7.4[added]
     * 
     * @param string $table
     * @param string $constraint = NULL
     * 
     * @return string
     */
    public function dropPrimaryKey($table, $constraint = NULL)
    {
        return 'ALTER TABLE ' . $table . ' DROP ' . $this->db()->constraint() . ' ' . $constraint . ';';
    }

    /**
     * Drop Foreign Key
     * 
     * 5.7.4[added]
     * 
     * @param string $table
     * @param string $constraint = NULL
     * 
     * @return string
     */
    public function dropForeignKey($table, $constraint = NULL)
    {
        return 'ALTER TABLE ' . $table . ' DROP ' . $this->db()->constraint() . $constraint . ';';
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
        return 'ALTER TABLE ' . $table . ' MODIFY ' . $this->_extractColumn($columns) . ';';
    }

    /**
     * Rename Column
     * 
     * @param string $table
     * @param array  $columns
     * 
     * @return string
     */
    public function renameColumn($table, $columns)
    {
        return 'ALTER TABLE ' . $table . ' CHANGE COLUMN ' . $this->_extractColumn($columns) . ';';
    }

    /**
     * Protected added constraint
     */
    protected function addedConstraint($string = NULL)
    {
        if( $string !== NULL )
        {
            return $this->db()->constraint() . ' ' . $string . ' ';
        }

        return NULL;
    }

    /**
     * Protected DB class
     * 
     * @return ZN\Database\DB
     */
    protected function db()
    {
        return Singleton::class('ZN\Database\DB');
    }

    /**
     * Protected common conversion
     */
    protected function commonConversion($key, &$value)
    {
        $value = preg_replace('/('.$this->db()->constraint().'.*?)*('.(rtrim($this->db()->foreignKey())).')/', ', $1 $2 ('.$key.')', $value);
    }

    /**
     * protected Syntax
     */
    protected function _syntax($column, $sep = NULL)
    {
        return key($column) . ' ' . $sep . ' ' . (is_array($cols = current($column)) ? implode(' ', $cols) : $cols);
    }

    /**
     * Protected Extract Column
     */
    protected function _extractColumn($columns)
    {
        $con = NULL;

        foreach( $columns as $column => $values )
        {
            $colvals = '';

            if( is_array($values) )
            {
                foreach( $values as $val )
                {
                    $colvals .= ' ' . $val;
                }
            }
            else
            {
                $colvals .= ' ' . $values;
            }

            $con .= $column . $colvals . ',';
        }

        return rtrim($con, ',');
    }

    /**
     * Protected Extras
     */
    protected function _extras($extras)
    {
        if( is_array($extras) )
        {
            $extraCodes = ' ' . implode(' ', $extras) . ';'; // @codeCoverageIgnore
        }
        elseif( is_string($extras) )
        {
            $extraCodes = ' ' . $extras . ';';
        }
        else
        {
            $extraCodes = '';
        }

        return $extraCodes;
    }
}
