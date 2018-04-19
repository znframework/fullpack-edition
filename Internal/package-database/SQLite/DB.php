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

use SQLite3;
use stdClass;
use Exception;
use ZN\Support;
use ZN\ErrorHandling\Errors;
use ZN\Database\DriverMappingAbstract;
use ZN\Database\Exception\ConnectionErrorException;

class DB extends DriverMappingAbstract
{
    /**
     * Keep Operators
     * 
     * @var array
     */
    protected $operators =
    [
        'like' => '%'
    ];

    /**
     * Keep Statements
     * 
     * @var array
     */
    protected $statements =
    [
        'autoincrement' => 'AUTOINCREMENT',
        'primarykey'    => 'PRIMARY KEY',
        'foreignkey'    => 'FOREIGN KEY',
        'unique'        => 'UNIQUE',
        'null'          => 'NULL',
        'notnull'       => 'NOT NULL',
        'exists'        => 'EXISTS',
        'notexists'     => 'NOT EXISTS',
        'constraint'    => 'CONSTRAINT',
        'default'       => 'DEFAULT'
    ];

    /**
     * Keep Variable Types
     * 
     * @var array
     */
    protected $variableTypes =
    [
        'int'           => 'INTEGER',
        'smallint'      => 'SMALLINT',
        'tinyint'       => 'TINYINT',
        'mediumint'     => 'MEDIUMINT',
        'bigint'        => 'BIGINT',
        'decimal'       => 'DECIMAL',
        'double'        => 'DOUBLE',
        'float'         => 'FLOAT',
        'char'          => 'CHARACTER',
        'varchar'       => 'VARCHAR',
        'tinytext'      => 'VARCHAR(255)',
        'text'          => 'TEXT',
        'mediumtext'    => 'CLOB',
        'longtext'      => 'BLOB',
        'date'          => 'DATE',
        'datetime'      => 'DATETIME',
        'time'          => 'DATETIME',
        'timestamp'     => 'DATETIME'
    ];

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        Support::extension('SQLite3');
    }

    /**
     * Connection
     * 
     * @param array $config = []
     */
    public function connect($config = [])
    {
        $this->config = $config;

        try
        {
            $this->connect = ( ! empty($this->config['password']) )
                             ? new SQLite3($this->config['database'], SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, $this->config['password'])
                             : new SQLite3($this->config['database']);
        }
        catch( Exception $e )
        {
            throw new ConnectionErrorException;
        }
    }

    /**
     * Execute
     * 
     * @param string $query
     * @param array  $security = NULL
     * 
     * @return bool
     */
    public function exec($query, $security = NULL)
    {
        if( empty($query) )
        {
            return false;
        }

        return $this->connect->exec($query);
    }

    /**
     * Multiple Queries
     * 
     * @param string $query
     * @param array  $security = NULL
     * 
     * @return bool
     */
    public function multiQuery($query, $security = NULL)
    {
        return $this->query($query, $security);
    }

    /**
     * Query
     * 
     * @param string $query
     * @param array  $security = NULL
     * 
     * @return bool
     */
    public function query($query, $security = [])
    {
        return $this->query = $this->connect->query($query);
    }

    /**
     * Start Transaction Query
     * 
     * @return bool
     */
    public function transStart()
    {
        return $this->connect->exec('BEGIN TRANSACTION');
    }

    /**
     * Rollback Transaction Query
     * 
     * @return bool
     */
    public function transRollback()
    {
        return $this->connect->exec('ROLLBACK');
    }

    /**
     * Commit Transaction Query
     * 
     * @return bool
     */
    public function transCommit()
    {
        return $this->connect->exec('END TRANSACTION');
    }

    /**
     * Insert Last ID
     * 
     * @return int|false
     */
    public function insertID()
    {
        if( empty($this->connect) )
        {
            return false;
        }

        return $this->connect->lastInsertRowID();
    }

    /**
     * Returns column data
     * 
     * @param string $column
     * 
     * @return array|object
     */
    public function columnData($col = '')
    {
        if( empty($this->query) )
        {
            return false;
        }

        $dataTypes =
        [
            SQLITE3_INTEGER => 'integer',
            SQLITE3_FLOAT   => 'float',
            SQLITE3_TEXT    => 'text',
            SQLITE3_BLOB    => 'blob',
            SQLITE3_NULL    => 'null'
        ];

        $columns   = [];
        $numFields = $this->numFields();

        for( $i = 0; $i < $numFields; $i++ )
        {
            $type      = $this->query->columnType($i);
            $fieldName = $this->query->columnName($i);

            $columns[$fieldName]             = new stdClass();
            $columns[$fieldName]->name       = $fieldName;
            $columns[$fieldName]->type       = $dataTypes[$type] ?? $type;
            $columns[$fieldName]->maxLength  = NULL;
            $columns[$fieldName]->primaryKey = NULL;
            $columns[$fieldName]->default    = NULL;
        }

        return $columns[$col] ?? $columns;
    }

    /**
     * Numrows
     * 
     * @return int
     */
    public function numRows()
    {
        if( ! empty($this->query) )
        {
            return count($this->result());
        }

        return 0;
    }

     /**
     * Returns columns
     * 
     * @return array
     */
    public function columns()
    {
        if( empty($this->query) )
        {
            return [];
        }

        $columns   = [];
        $numFields = $this->numFields();

        for( $i = 0; $i < $numFields; $i++ )
        {
            $columns[] = $this->query->columnName($i);
        }

        return $columns;
    }

    /**
     * Numfields
     * 
     * @return int
     */
    public function numFields()
    {
        if( ! empty($this->query) )
        {
            return $this->query->numColumns();
        }
        else
        {
            return 0;
        }
    }

    /**
     * Real Escape String 
     * 
     * @param string $data
     * 
     * @return string|false
     */
    public function realEscapeString($data)
    {
        if( empty($this->connect) )
        {
            return $data;
        }

        return $this->connect->escapeString($data);
    }

    /**
     * Returns a string description of the last error.
     * 
     * @return string|false
     */
    public function error()
    {
        if( ! empty($this->connect) )
        {
            return $this->connect->lastErrorMsg();
        }
        else
        {
            return false;
        }
    }

    /**
     * Fetch a result row as an associative, a numeric array, or both
     * 
     * @return mixed
     */
    public function fetchArray()
    {
        if( ! empty($this->query) )
        {
            return $this->query->fetchArray(SQLITE3_BOTH);
        }
        else
        {
            return [];
        }
    }

    /**
     * Fetch a result row as an associative array
     * 
     * @return mixed
     */
    public function fetchAssoc()
    {
        if( ! empty($this->query) )
        {
            return $this->query->fetchArray(SQLITE3_ASSOC);
        }
        else
        {
            return [];
        }
    }

    /**
     * Get a result row as an enumerated array
     * 
     * @return mixed
     */
    public function fetchRow()
    {
        if( ! empty($this->query) )
        {
            return $this->query->fetchArray();
        }
        else
        {
            return [];
        }
    }

    /**
     * Gets the number of affected rows in a previous MySQL operation
     * 
     * @return int
     */
    public function affectedRows()
    {
        if( ! empty($this->connect) )
        {
            return  $this->connect->changes();
        }
        else
        {
            return 0;
        }
    }

    /**
     * Closes a previously opened database connection
     * 
     * @return bool
     */
    public function close()
    {
        if( ! empty($this->connect) )
        {
            @$this->connect->close();
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns the version of the MySQL server as an integer
     * 
     * @return int
     */
    public function version($v = 'versionString')
    {
        if( ! empty($this->connect) )
        {
            $version = SQLite3::version();

            return $version[$v];
        }
        else
        {
            return false;
        }
    }
}
