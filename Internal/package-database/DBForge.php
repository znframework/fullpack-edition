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
            case 'Create'  : $method = 'createTable';
            case 'Drop'    : $method = 'dropTable'  ;
            case 'Alter'   : $method = 'alterTable' ;
            case 'Rename'  : $method = 'renameTable';
            case 'Truncate': $method = 'truncate'   ;
            default        : Support::classMethod(get_called_class(), $originMethodName);
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

        $this->forge = $this->_drvlib('Forge', $settings);
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
    public function createDatabase(String $dbname, $extras = NULL)
    {
        $query = $this->forge->createDatabase($dbname, $this->_p($extras, 'extras'));

        return $this->_runExecQuery($query);
    }

    /**
     * Drop Database
     * 
     * @param string $dbname
     * 
     * @return bool
     */
    public function dropDatabase(String $dbname)
    {
        $query = $this->forge->dropDatabase($dbname);

        return $this->_runExecQuery($query);
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
    public function createTable(String $table = NULL, Array $columns = NULL, $extras = NULL)
    {
        $query = $this->forge->createTable($this->_p($table), $this->_p($columns, 'column'), $this->_p($extras, 'extras'));

        return $this->_runExecQuery($query);
    }

    /**
     * Create Temporary Table
     * 
     * @param string $tabşe
     * @param array  $columns
     * @param string $extras
     * 
     * @return bool
     */
    public function createTempTable(String $table = NULL, Array $columns = NULL, $extras = NULL)
    {
        $query = $this->forge->createTempTable($this->_p($table), $this->_p($columns, 'column'), $this->_p($extras, 'extras'));

        return $this->_runExecQuery($query);
    }

    /**
     * Drop Table
     * 
     * @param string $table
     * 
     * @return bool
     */
    public function dropTable(String $table = NULL)
    {
        $query = $this->forge->dropTable($this->_p($table));

        return $this->_runExecQuery($query);
    }

    /**
     * Alter Table
     * 
     * @param string $table
     * @param mixed  $condition
     * 
     * @return bool
     */
    public function alterTable(String $table = NULL, Array $condition = NULL)
    {
        $table = $this->_p($table);
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
    public function renameTable(String $name, String $newName)
    {
        $query = $this->forge->renameTable($this->_p($name, 'prefix'), $this->_p($newName, 'prefix'));

        return $this->_runExecQuery($query);
    }

    /**
     * Truncate
     * 
     * @param string $table
     * 
     * @return bool
     */
    public function truncate(String $table = NULL)
    {
        $query = $this->forge->truncate($this->_p($table));

        return $this->_runExecQuery($query);
    }

    /**
     * Add Column
     * 
     * @param string $table
     * @param array  $columns
     * 
     * @return bool
     */
    public function addColumn(String $table = NULL, Array $columns = NULL)
    {
        $query = $this->forge->addColumn($this->_p($table), $this->_p($columns, 'column'));

        return $this->_runExecQuery($query);
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
     */
    public function startAutoIncrement(String $table, Int $start = 0)
    {
        $query = $this->forge->startAutoIncrement($this->_p($table), $start);

        return $this->_runExecQuery($query);
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
     */
    public function addAutoIncrement(String $table, String $column = 'id', Int $start = NULL)
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
    public function addPrimaryKey(String $table, String $columns, String $constraint = NULL)
    {
        $query = $this->forge->addPrimaryKey($this->_p($table), $columns, $constraint);

        return $this->_runExecQuery($query);
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
    public function addForeignKey(String $table, String $columns, String $reftable, String $refcolumn, String $constraint = NULL)
    {
        $query = $this->forge->addForeignKey($this->_p($table), $columns, $reftable, $refcolumn, $constraint);

        return $this->_runExecQuery($query);
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
    public function dropPrimaryKey(String $table = NULL, String $constraint = NULL)
    {
        $query = $this->forge->dropPrimaryKey($this->_p($table), $constraint);

        return $this->_runExecQuery($query);
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
    public function dropForeignKey(String $table = NULL, String $constraint = NULL)
    {
        $query = $this->forge->dropForeignKey($this->_p($table), $constraint);

        return $this->_runExecQuery($query);
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
    public function createIndex(String $indexName, String $table, String $columns)
    {
        $query = $this->forge->createIndex($indexName, $this->_p($table), $columns);

        return $this->_runExecQuery($query);
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
    public function createUniqueIndex(String $indexName, String $table, String $columns)
    {
        $query = $this->forge->createUniqueIndex($indexName, $this->_p($table), $columns);

        return $this->_runExecQuery($query);
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
    public function createFulltextIndex(String $indexName, String $table, String $columns)
    {
        $query = $this->forge->createFulltextIndex($indexName, $this->_p($table), $columns);

        return $this->_runExecQuery($query);
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
    public function createSpatialIndex(String $indexName, String $table, String $columns)
    {
        $query = $this->forge->createSpatialIndex($indexName, $this->_p($table), $columns);

        return $this->_runExecQuery($query);
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
    public function dropIndex(String $indexName, String $table = NULL)
    {
        $query = $this->forge->dropIndex($indexName, $this->_p($table));

        return $this->_runExecQuery($query);
    }

    /**
     * Drop Column
     * 
     * @param string $table
     * @param array  $columns
     * 
     * @return bool
     */
    public function dropColumn(String $table = NULL, $columns = NULL)
    {
        $columns = $this->_p($columns, 'column');

        if( ! is_array($columns) )
        {
            $query = $this->forge->dropColumn($this->_p($table), $columns);

            return $this->_runExecQuery($query);
        }
        else
        {
            foreach( $columns as $key => $col )
            {
                if( ! is_numeric($key) )
                {
                    $col = $key;
                }

                $query = $this->forge->dropColumn($this->_p($table), $col);

                $this->_runExecQuery($query);
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
    public function modifyColumn(String $table = NULL, Array $columns = NULL)
    {
        $query = $this->forge->modifyColumn($this->_p($table), $this->_p($columns, 'column'));

        return $this->_runExecQuery($query);
    }

    /**
     * Rename Column
     * 
     * @param string $table
     * @param array  $columns
     * 
     * @return bool
     */
    public function renameColumn(String $table = NULL , Array $columns = NULL)
    {
        $query = $this->forge->renameColumn($this->_p($table), $this->_p($columns, 'column'));

        return $this->_runExecQuery($query);
    }
}
