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

use stdClass;
use ZN\Support;
use ZN\Security;
use ZN\ErrorHandling\Errors;
use ZN\Database\Exception\ConnectionErrorException;
use ZN\Database\DriverMappingAbstract;

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
        'autoincrement' => 'IDENTITY(1,1)',
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
        'int'           => ':INT',
        'smallint'      => ':SMALLINT',
        'tinyint'       => ':TINYINT',
        'mediumint'     => ':INT',
        'bigint'        => ':BIGINT',
        'decimal'       => 'DECIMAL',
        'double'        => 'FLOAT',
        'float'         => 'FLOAT',
        'char'          => 'CHAR',
        'varchar'       => 'VARCHAR',
        'tinytext'      => ':VARCHAR(255)',
        'text'          => ':VARCHAR(65535)',
        'mediumtext'    => ':VARCHAR(16277215)',
        'longtext'      => ':VARCHAR(16277215)',
        'date'          => ':DATE',
        'datetime'      => ':DATETIME',
        'time'          => ':TIME',
        'timestamp'     => ':TIMESTAMP'
    ];

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        Support::func('sqlsrv_connect', 'SQL Server');
    }

    /**
     * Connection
     * 
     * @param array $config = []
     */
    public function connect($config = [])
    {
        $this->config = $config;

        $server =   ( ! empty($this->config['server']) )
                    ? $this->config['server'] // @codeCoverageIgnore
                    : $this->config['host'];

        if( ! empty($this->config['port']) )
        {
            $server .= ', '.$this->config['port'];
        }

        $charset = $this->config['charset'] === 'utf8' ? 'utf-8' : $this->config['charset'];

        $connection = 
        [
            'UID'                   => $this->config['user'],
            'PWD'                   => $this->config['password'],
            'Database'              => $this->config['database'],
            'ConnectionPooling'     => $this->config['pconnect'] === true ? 1 : 0,
            'CharacterSet'          => $charset ?: 'utf-8',
            'Encrypt'               => $this->config['encode'] ?: false,
            'ReturnDatesAsStrings'  => 1
        ];

        $this->connect = @sqlsrv_connect($server, $connection);

        if( empty($this->connect) )
        {
            throw new ConnectionErrorException(NULL, sqlsrv_errors(SQLSRV_ERR_ERRORS)[0]['message']); // @codeCoverageIgnore
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

        return sqlsrv_query($this->connect, $query);
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
    public function query($query, $security = NULL)
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
        return sqlsrv_begin_transaction($this->connect);
    }

    /**
     * Rollback Transaction Query
     * 
     * @return bool
     */
    public function transRollback()
    {
        return sqlsrv_rollback($this->connect);
    }

    /**
     * Commit Transaction Query
     * 
     * @return bool
     */
    public function transCommit()
    {
        return sqlsrv_commit($this->connect);
    }

    /**
     * Insert Last ID
     * 
     * @return int|false
     */
    public function insertID()
    {
        $this->query('SELECT @@IDENTITY AS insert_id');
        
        return $this->fetchAssoc()['insert_id'];
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

        $columns = [];

        foreach( sqlsrv_field_metadata($this->query) as $field )
        {
            $fieldName = $field['Name'];

            $columns[$fieldName]             = new stdClass();
            $columns[$fieldName]->name       = $fieldName;
            $columns[$fieldName]->type       = $field['Type'];
            $columns[$fieldName]->maxLength  = $field['Size'];
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
        $this->query('select @@RowCount');

        return $this->fetchRow()[0] ?? false;
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

        $columns = [];

        $getFieldData = sqlsrv_field_metadata($this->query);

        foreach( $getFieldData as $field )
        {
            $columns[] = $field['Name'];
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
            return sqlsrv_num_fields($this->query);
        }
        else
        {
            return 0; // @codeCoverageIgnore
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
            $error = sqlsrv_errors(SQLSRV_ERR_ERRORS)[0] ?? [];

            return ! empty($error['code']) ? ($error['code'] === 15477 ? false : ($error['message'] ?: false)) : false;
        }
        else
        {
            return false; // @codeCoverageIgnore
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
            return sqlsrv_fetch_array($this->query, SQLSRV_FETCH_BOTH);
        }
        else
        {
            return []; // @codeCoverageIgnore
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
            return sqlsrv_fetch_array($this->query, SQLSRV_FETCH_ASSOC);
        }
        else
        {
            return []; // @codeCoverageIgnore
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
            return sqlsrv_fetch_array($this->query, SQLSRV_FETCH_NUMERIC);
        }
        else
        {
            return []; // @codeCoverageIgnore
        }
    }

    /**
     * Gets the number of affected rows in a previous MySQL operation
     * 
     * @return int
     */
    public function affectedRows()
    {
        if( ! empty($this->query) )
        {
            return sqlsrv_rows_affected($this->query);
        }
        else
        {
            return 0; // @codeCoverageIgnore
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
            return sqlsrv_server_info($this->connect)['SQLServerVersion'];
        }
        else
        {
            return false; // @codeCoverageIgnore
        }
    }

    /**
     * Limit
     * 
     * @param int $start = NULL
     * @param int $limit = 0
     * 
     * @return DB
     * 
     * @codeCoverageIgnore
     */
    public function limit($start = NULL, int $limit = 0)
    {
        if( $limit === 0 )
        {
            $limit = $start;
            $start = 0;
        }

        return ' OFFSET ' . $start . ' ROWS FETCH NEXT ' . $limit . ' ROWS ONLY';
    }

    /**
     * Protected Clean Limit
     * 
     * @codeCoverageIgnore
     */
    public function cleanLimit($data)
    {
        return preg_replace('/OFFSET\s+[0-9]+\s+ROWS\sFETCH\sNEXT\s+[0-9]+\s+ROWS\sONLY/xi', '', $data);
    }

    /**
     * Protected Get Limit Values
     * 
     * @codeCoverageIgnore
     */
    public function getLimitValues($data)
    {
        preg_match('/OFFSET\s+(?<start>[0-9]+)\s+ROWS\sFETCH\sNEXT\s+(?<limit>[0-9]+)\s+ROWS\sONLY/xi', $data ?? '', $match);

        return $match;
    }
}
