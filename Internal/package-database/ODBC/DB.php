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


use stdClass;
use ZN\Support;
use ZN\Security;
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
        'like' => '*'
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
        'mediumint'     => 'INTEGER',
        'bigint'        => 'BIGINT',
        'decimal'       => 'DECIMAL',
        'double'        => 'DOUBLE PRECISION',
        'float'         => 'FLOAT',
        'char'          => 'CHAR',
        'varchar'       => 'VARCHAR',
        'tinytext'      => 'VARCHAR(255)',
        'text'          => 'VARCHAR(65535)',
        'mediumtext'    => 'VARCHAR(16277215)',
        'longtext'      => 'VARCHAR(16277215)',
        'date'          => 'DATE',
        'datetime'      => 'UTCDATETIME',
        'time'          => 'TIME',
        'timestamp'     => 'TIMESTAMP'
    ];

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        Support::func('odbc_connect', 'Microsoft Access(ODBC)');
    }

    /**
     * Connection
     * 
     * @param array $config = []
     */
    public function connect($config = [])
    {
        $this->config = $config;

        $dsn =  ( ! empty($this->config['dsn']) )
                ? $this->config['dsn']
                : 'DRIVER='.$this->config['host'].';SERVER='.$this->config['server'].';DATABASE='.$this->config['database'];


        $this->connect =    ( $this->config['pconnect'] === true )
                            ? @odbc_pconnect($dsn , $this->config['user'], $this->config['password'])
                            : @odbc_connect($dsn , $this->config['user'], $this->config['password']);

        if( empty($this->connect) )
        {
            throw new ConnectionErrorException();
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

        return odbc_exec($this->connect, $query);
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
        $this->query = odbc_prepare($this->connect, $query);
        return odbc_execute($this->query, $security);
    }

    /**
     * Start Transaction Query
     * 
     * @return bool
     */
    public function transStart()
    {
        return odbc_autocommit($this->connect, false);
    }

     /**
     * Rollback Transaction Query
     * 
     * @return bool
     */
    public function transRollback()
    {
        $rollback = odbc_rollback($this->connect);
        odbc_autocommit($this->connect, true);
        return $rollback;
    }

    /**
     * Commit Transaction Query
     * 
     * @return bool
     */
    public function transCommit()
    {
        $commit = odbc_commit($this->connect);
        odbc_autocommit($this->connect, true);
        return $commit;
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

        $columns   = [];
        $numFields = $this->numFields();

        for( $index = 1; $index <= $numFields; $index++ )
        {
            $fieldName = odbc_field_name($this->query, $index);

            $columns[$fieldName]             = new stdClass();
            $columns[$fieldName]->name       = $fieldName;
            $columns[$fieldName]->type       = odbc_field_type($this->query, $index);
            $columns[$fieldName]->maxLength  = odbc_field_len($this->query, $index);
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
            return odbc_num_rows($this->query);
        }
        else
        {
            return 0;
        }
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

        for( $index = 1;  $index <= $numFields; $index++ )
        {
            $columns[] = odbc_field_name($this->query, $index);
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
            return odbc_num_fields($this->query);
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
    public function realEscapeString($data = '')
    {
        return Security\Injection::escapeStringEncode($data);
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
            return odbc_error($this->connect);
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
            return odbc_fetch_array($this->query);
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
            return odbc_fetch_array($this->query);
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
            return odbc_fetch_row($this->query);
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
            return 0;
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
            @odbc_close($this->connect);
        }
    }

    /**
     * Returns the version of the MySQL server as an integer
     * 
     * @return int
     */
    public function version()
    {
        if( ! empty($this->connect) )
        {
            return false;
        }
    }
}
