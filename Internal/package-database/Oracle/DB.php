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
        'like' => '%'
    ];

    /**
     * Keep Statements
     * 
     * @var array
     */
    protected $statements =
    [
        'autoincrement' => 'CREATE SEQUENCE % MINVALUE 1 STARVALUE WITH 1 INCREMENT BY 1;',
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
        'int'           => 'NUMERIC',
        'smallint'      => 'NUMERIC',
        'tinyint'       => 'NUMERIC',
        'mediumint'     => 'NUMERIC',
        'bigint'        => 'NUMERIC',
        'decimal'       => 'DECIMAL',
        'double'        => 'BINARY_DOUBLE',
        'float'         => 'BINARY_FLOAT',
        'char'          => 'CHAR',
        'varchar'       => 'VARCHAR2',
        'tinytext'      => 'VARCHAR2(255)',
        'text'          => 'VARCHAR2(65535)',
        'mediumtext'    => 'VARCHAR2(16277215)',
        'longtext'      => 'CLOB',
        'date'          => 'DATE',
        'datetime'      => 'TIMESTAMP',
        'time'          => 'TIMESTAMP',
        'timestamp'     => 'TIMESTAMP'
    ];

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        Support::func('oci_connect', 'Oracle 8');
    }

    /**
     * Connection
     * 
     * @param array $config = []
     */
    public function connect($config = [])
    {
        $this->config = $config;

        $dsn =  ( ! empty($this->config['dsn']))
                ? $this->config['dsn']
                : $this->config['host'];

        $connectMethod = $this->config['pconnect'] === true ? 'oci_pconnect' : 'oci_connect';

        $this->connect = $connectMethod($this->config['user'], $this->config['password'], $dsn);

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

        $que = oci_parse($this->connect, $query);
        oci_execute($que);

        return $que;
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
        $this->query = oci_parse($this->connect, $query);
        return oci_execute($this->query);
    }

    /**
     * Start Transaction Query
     * 
     * @return bool
     */
    public function transStart()
    {
        $this->exec(OCI_NO_AUTO_COMMIT);
        return true;
    }

    /**
     * Rollback Transaction Query
     * 
     * @return bool
     */
    public function transRollback()
    {
        oci_rollback($this->connect);
        return $this->exec(OCI_COMMIT_ON_SUCCESS);
    }

    /**
     * Commit Transaction Query
     * 
     * @return bool
     */
    public function transCommit()
    {
        oci_commit($this->connect);
        return $this->exec(OCI_COMMIT_ON_SUCCESS);
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

        for( $i = 1; $i <= $numFields; $i++ )
        {
            $fieldName = oci_field_name($this->query, $i);

            $columns[$fieldName]             = new stdClass();
            $columns[$fieldName]->name       = $fieldName;
            $columns[$fieldName]->type       = oci_field_type($this->query, $i);
            $columns[$fieldName]->maxLength  = oci_field_size($this->query, $i);
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
            return oci_num_rows($this->query);
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

        for( $i = 0; $i < $numFields; $i++ )
        {
                $columns[] = oci_field_name($this->query, $i);
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
            return oci_num_fields($this->query);
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
            return  oci_error($this->connect)['message'];
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
            return oci_fetch_array($this->query);
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
            return oci_fetch_assoc($this->query);
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
            return oci_fetch_row($this->query);
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
            @oci_close($this->connect);
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
    public function version()
    {
        if( ! empty($this->connect) )
        {
            return oci_server_version($this->connect);
        }
        else
        {
            return false;
        }
    }
}
