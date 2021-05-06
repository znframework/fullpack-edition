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

use ZN\Support;
use ZN\Datatype;
use ZN\Singleton;

class DBForge extends Connection
{   
    /**
     * @var array
     */
    protected $extras;

    /**
     * Keeps Forge Driver
     * 
     * @var object
     */
    protected $forge;

    /**
     * Magic Call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @param mixed
     */
    public function __call($method, $parameters)
    {
        $split  = Datatype::splitUpperCase($originMethodName = $method);
        $table  = $split[0];
        $method = $split[1] ?? NULL;

        switch($method)
        {
            case 'Create'  : $method = 'createTable'; break;
            case 'Drop'    : $method = 'dropTable'  ; break;
            case 'Alter'   : $method = 'alterTable' ; break;
            case 'Rename'  : $method = 'renameTable'; break;
            case 'Truncate': $method = 'truncate'   ; break;
            default        : Support::classMethod(get_called_class(), $originMethodName); // @codeCoverageIgnore
        }

        return $this->$method($table, ...$parameters);
    }

    /**
     * Magic Constructor
     * 
     * @param array $settings
     */
    public function __construct($settings = [])
    {
        parent::__construct($settings);

        $this->forge = $this->getDriver('Forge', $settings);
    }

    /**
     * Create Table & Database Extras
     * 
     * @param mixed $extras
     * 
     * @return DBForge
     */
    public function extras($extras) : DBForge
    {
        $this->extras = $this->forge->extras($extras);

        return $this;
    }

    /**
     * Create Database
     * 
     * @param string $dbname
     * @param string $extras
     * 
     * @return bool
     */
    public function createDatabase(string $dbname, $extras = NULL)
    {
        $query = $this->forge->createDatabase($dbname, $this->addPrefixForTableAndColumn($extras, 'extras'));

        return $this->runExecQuery($query);
    }

    /**
     * Drop Database
     * 
     * @param string $dbname
     * 
     * @return bool
     */
    public function dropDatabase(string $dbname)
    {
        $query = $this->forge->dropDatabase($dbname);

        return $this->runExecQuery($query);
    }

    /**
     * Create Table
     * 
     * @param string $tabşe
     * @param array  $columns
     * @param string $extras
     * 
     * @return bool
     */
    public function createTable(string $table = NULL, array $columns = NULL, $extras = NULL)
    {
        $query = $this->forge->createTable($this->addPrefixForTableAndColumn($table), $this->addPrefixForTableAndColumn($columns, 'column'), $this->addPrefixForTableAndColumn($extras, 'extras'));

        return $this->runExecQuery($query);
    }

    /**
     * Create Temporary Table
     * 
     * @param string $tabşe
     * @param array  $columns
     * @param string $extras
     * 
     * @return bool
     * 
     * @codeCoverageIgnore
     */
    public function createTempTable(string $table = NULL, array $columns = NULL, $extras = NULL)
    {
        $query = $this->forge->createTempTable($this->addPrefixForTableAndColumn($table), $this->addPrefixForTableAndColumn($columns, 'column'), $this->addPrefixForTableAndColumn($extras, 'extras'));

        return $this->runExecQuery($query);
    }

    /**
     * Drop Table
     * 
     * @param string $table
     * 
     * @return bool
     */
    public function dropTable(string $table = NULL)
    {
        $query = $this->forge->dropTable($this->addPrefixForTableAndColumn($table));

        return $this->runExecQuery($query);
    }

    /**
     * Alter Table
     * 
     * @param string $table
     * @param mixed  $condition
     * 
     * @return bool
     * 
     * @codeCoverageIgnore
     */
    public function alterTable(string $table = NULL, array $condition = NULL)
    {
        $table = $this->addPrefixForTableAndColumn($table);
        $key   = key($condition);

        return $this->$key($table, $condition[$key]);
    }

    /**
     * Rename Table
     * 
     * @param string $name
     * @param string $newname
     * 
     * @return bool
     */
    public function renameTable(string $name, string $newName)
    {
        $query = $this->forge->renameTable($this->addPrefixForTableAndColumn($name, 'prefix'), $this->addPrefixForTableAndColumn($newName, 'prefix'));

        return $this->runExecQuery($query);
    }

    /**
     * Truncate
     * 
     * @param string $table
     * 
     * @return bool
     */
    public function truncate(string $table = NULL)
    {
        $query = $this->forge->truncate($this->addPrefixForTableAndColumn($table));

        return $this->runExecQuery($query);
    }

    /**
     * Add Column
     * 
     * @param string $table
     * @param array  $columns
     * 
     * @return bool
     */
    public function addColumn(string $table = NULL, array $columns = NULL)
    {
        $query = $this->forge->addColumn($this->addPrefixForTableAndColumn($table), $this->addPrefixForTableAndColumn($columns, 'column'));

        return $this->runExecQuery($query);
    }

    /**
     * Start Auto Increment
     *
     * 5.7.4[added]
     * 
     * @param string $table
     * @param int    $start = 0
     * 
     * @return bool
     * 
     * @codeCoverageIgnore
     */
    public function startAutoIncrement(string $table, int $start = 0)
    {
        $query = $this->forge->startAutoIncrement($this->addPrefixForTableAndColumn($table), $start);

        return $this->runExecQuery($query);
    }

    /**
     * Start Auto Increment
     *
     * 5.7.4[added]
     * 
     * @param string $table
     * @param int    $start = 0
     * 
     * @return bool
     * 
     * @codeCoverageIgnore
     */
    public function addAutoIncrement(string $table, string $column = 'id', int $start = NULL)
    {
        if( $start !== NULL )
        {
            $this->startAutoIncrement($table, $start);
        }

        return $this->modifyColumn($table, [$column  => [($db = Singleton::class('ZN\Database\DB'))->int(), $db->autoIncrement()]]);
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
     * @return bool
     */
    public function addPrimaryKey(string $table, string $columns, string $constraint = NULL)
    {
        $query = $this->forge->addPrimaryKey($this->addPrefixForTableAndColumn($table), $columns, $constraint);

        return $this->runExecQuery($query);
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
     * @return bool
     */
    public function addForeignKey(string $table, string $columns, string $reftable, string $refcolumn, string $constraint = NULL)
    {
        $query = $this->forge->addForeignKey($this->addPrefixForTableAndColumn($table), $columns, $reftable, $refcolumn, $constraint);

        return $this->runExecQuery($query);
    }

    /**
     * Drop Primary Key
     * 
     * 5.7.4[added]
     * 
     * @param string $table 
     * @param string $constraint = NULL
     * 
     * @return bool
     */
    public function dropPrimaryKey(string $table = NULL, string $constraint = NULL)
    {
        $query = $this->forge->dropPrimaryKey($this->addPrefixForTableAndColumn($table), $constraint);

        return $this->runExecQuery($query);
    }

    /**
     * Drop Foreign Key
     * 
     * 5.7.4[added]
     * 
     * @param string $table 
     * @param string $constraint = NULL
     * 
     * @return bool
     */
    public function dropForeignKey(string $table = NULL, string $constraint = NULL)
    {
        $query = $this->forge->dropForeignKey($this->addPrefixForTableAndColumn($table), $constraint);

        return $this->runExecQuery($query);
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
     * @return bool
     */
    public function createIndex(string $indexName, string $table, string $columns)
    {
        $query = $this->forge->createIndex($indexName, $this->addPrefixForTableAndColumn($table), $columns);

        return $this->runExecQuery($query);
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
     * @return bool
     */
    public function createUniqueIndex(string $indexName, string $table, string $columns)
    {
        $query = $this->forge->createUniqueIndex($indexName, $this->addPrefixForTableAndColumn($table), $columns);

        return $this->runExecQuery($query);
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
     * @return bool
     */
    public function createFulltextIndex(string $indexName, string $table, string $columns)
    {
        $query = $this->forge->createFulltextIndex($indexName, $this->addPrefixForTableAndColumn($table), $columns);

        return $this->runExecQuery($query);
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
     * @return bool
     */
    public function createSpatialIndex(string $indexName, string $table, string $columns)
    {
        $query = $this->forge->createSpatialIndex($indexName, $this->addPrefixForTableAndColumn($table), $columns);

        return $this->runExecQuery($query);
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
    public function dropIndex(string $indexName, string $table = NULL)
    {
        $query = $this->forge->dropIndex($indexName, $this->addPrefixForTableAndColumn($table));

        return $this->runExecQuery($query);
    }

    /**
     * Drop Column
     * 
     * @param string $table
     * @param array  $columns
     * 
     * @return bool
     */
    public function dropColumn(string $table = NULL, $columns = NULL)
    {
        $columns = $this->addPrefixForTableAndColumn($columns, 'column');

        if( ! is_array($columns) )
        {
            $query = $this->forge->dropColumn($this->addPrefixForTableAndColumn($table), $columns);

            return $this->runExecQuery($query);
        }
        else
        {
            foreach( $columns as $key => $col )
            {
                if( ! is_numeric($key) )
                {
                    $col = $key; // @codeCoverageIgnore
                }

                $query = $this->forge->dropColumn($this->addPrefixForTableAndColumn($table), $col);

                $this->runExecQuery($query);
            }

            return ! (bool) $this->error();
        }
    }

    /**
     * MOdify Column
     * 
     * @param string $table
     * @param array  $columns
     * 
     * @return bool
     */
    public function modifyColumn(string $table = NULL, array $columns = NULL)
    {
        $query = $this->forge->modifyColumn($this->addPrefixForTableAndColumn($table), $this->addPrefixForTableAndColumn($columns, 'column'));

        return $this->runExecQuery($query);
    }

    /**
     * Rename Column
     * 
     * @param string $table
     * @param array  $columns
     * 
     * @return bool
     */
    public function renameColumn(string $table = NULL , array $columns = NULL)
    {
        $query = $this->forge->renameColumn($this->addPrefixForTableAndColumn($table), $this->addPrefixForTableAndColumn($columns, 'column'));

        return $this->runExecQuery($query);
    }
}
