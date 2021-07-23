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

use stdClass;
use ZN\Support;
use ZN\Database\Properties;
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
        'autoincrement' => 'BIGSERIAL',
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
        'int'           => ':INTEGER',
        'smallint'      => ':SMALLINT',
        'tinyint'       => ':SMALLINT',
        'mediumint'     => ':INTEGER',
        'bigint'        => ':BIGINT',
        'decimal'       => ':DECIMAL',
        'double'        => ':DOUBLE PRECISION',
        'float'         => ':NUMERIC',
        'char'          => 'CHARACTER',
        'varchar'       => 'CHARACTER VARYING',
        'tinytext'      => ':CHARACTER VARYING(255)',
        'text'          => ':TEXT',
        'mediumtext'    => ':TEXT',
        'longtext'      => ':TEXT',
        'date'          => ':DATE',
        'datetime'      => 'TIMESTAMP',
        'time'          => 'TIME',
        'timestamp'     => 'TIMESTAMP'
    ];

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        Support::func('pg_connect', 'Postgres');
    }

    /**
     * Connection
     * 
     * @param array $config = []
     */
    public function connect($config = [])
    {
        $this->config = $config;

        $dsn = 'host='.$this->config['host'].' ';

        if( ! empty($this->config['port']) )     $dsn .= 'port='.$this->config['port'].' ';
        if( ! empty($this->config['database']) ) $dsn .= 'dbname='.$this->config['database'].' ';
        if( ! empty($this->config['user']) )     $dsn .= 'user='.$this->config['user'].' ';
        if( ! empty($this->config['password']) ) $dsn .= 'password='.$this->config['password'].' ';

        if( ! empty($this->config['dsn']) )
        {
            $dsn = $this->config['dsn']; // @codeCoverageIgnore
        }

        $connectMethod = $this->config['pconnect'] === true ? 'pg_pconnect' : 'pg_connect';

        $this->connect = $connectMethod(rtrim($dsn));

        if( empty($this->connect) )
        {
            throw new ConnectionErrorException(NULL, 'connection'); // @codeCoverageIgnore
        }

        if( ! empty($this->config['charset']) )
        {
            $charset = $this->config['charset'] === 'utf8' ? 'UNICODE' : $this->config['charset'];

            pg_set_client_encoding($this->connect, $charset);
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
            return false; // @codeCoverageIgnore
        }
        
        set_error_handler(function(){});

        $return = pg_query($this->connect, $query);

        restore_error_handler();

        return $return;
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
        return (bool) $this->query($query, $security);
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
        return $this->query = $this->exec($query);
    }

    /**
     * Start Transaction Query
     * 
     * @return bool
     */
    public function transStart()
    {
        return (bool) pg_query($this->connect, 'BEGIN');
    }

    /**
     * Rollback Transaction Query
     * 
     * @return bool
     */
    public function transRollback()
    {
        return (bool) pg_query($this->connect, 'ROLLBACK');
    }

    /**
     * Commit Transaction Query
     * 
     * @return bool
     * 
     * @codeCoverageIgnore
     */
    public function transCommit()
    {
        return (bool) pg_query($this->connect, 'COMMIT');
    }

    /**
     * Insert Last ID
     * 
     * @return int|string
     * 
     * @codeCoverageIgnore
     */
    public function insertID()
    {
        if( empty($this->query) )
        {
            return false;
        }

        $returningId = Properties::$returningId; 

        Properties::$returningId = 'id';

        return $returningId === '*' ? (object) $this->fetchAssoc() : ( $this->fetchAssoc()[$returningId] ?? false );
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
            return false; // @codeCoverageIgnore
        }

        $columns   = [];
        $numFields = $this->numFields();

        for( $i = 0; $i < $numFields; $i++ )
        {
            $fieldName = pg_field_name($this->query, $i);

            $columns[$fieldName]             = new stdClass();
            $columns[$fieldName]->name       = $fieldName;
            $columns[$fieldName]->type       = pg_field_type($this->query, $i);
            $columns[$fieldName]->maxLength  = pg_field_size($this->query, $i);
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
        return ! empty($this->query) ? pg_num_rows($this->query) : 0;
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
            return []; // @codeCoverageIgnore
        }

        $columns   = [];
        $numFields = $this->numFields();

        for( $i = 0; $i < $numFields; $i++ )
        {
            $columns[] = pg_field_name($this->query, $i);
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
        return ! empty($this->query) ? pg_num_fields($this->query) : 0;
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
        return pg_escape_string($this->connect, $data);
    }

    /**
     * Returns a string description of the last error.
     * 
     * @return string|false
     */
    public function error()
    {
        return is_resource($this->connect) ? ( pg_last_error($this->connect) ?: false ) : false;
    }

    /**
     * Fetch a result row as an associative, a numeric array, or both
     * 
     * @return mixed
     */
    public function fetchArray()
    {
        return ! empty($this->query) ? pg_fetch_array($this->query) : [];
    }

    /**
     * Fetch a result row as an associative array
     * 
     * @return mixed
     */
    public function fetchAssoc()
    {
        return ! empty($this->query) ? pg_fetch_assoc($this->query) : [];
    }

    /**
     * Get a result row as an enumerated array
     * 
     * @return mixed
     */
    public function fetchRow()
    {
        return ! empty($this->query) ? pg_fetch_row($this->query) : [];
    }

    /**
     * Gets the number of affected rows in a previous MySQL operation
     * 
     * @return int
     */
    public function affectedRows()
    {
        return ! empty($this->query) ? pg_affected_rows($this->query) : 0;
    }

    /**
     * Returns the version of the MySQL server as an integer
     * 
     * @return int
     */
    public function version()
    {
        return is_resource($this->connect) ? pg_version($this->connect)['client'] : false;
    }

    /**
     * protected get insert extras by drvier
     * 
     * @codeCoverageIgnore
     */
    public function getInsertExtrasByDriver()
    {
        return ' RETURNING ' . Properties::$returningId . ';';
    }
}
