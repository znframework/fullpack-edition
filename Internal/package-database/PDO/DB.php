<?php namespace ZN\Database\PDO;
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
use ZN\Security;
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
        'autoincrement' => 'AUTO_INCREMENT',
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
        'int'           => 'INT',
        'smallint'      => 'SMALLINT',
        'tinyint'       => 'TINYINT',
        'mediumint'     => 'MEDIUMINT',
        'bigint'        => 'BIGINT',
        'decimal'       => 'DECIMAL',
        'double'        => 'DOUBLE',
        'float'         => 'FLOAT',
        'char'          => 'CHAR',
        'varchar'       => 'VARCHAR',
        'tinytext'      => 'TINYTEXT',
        'text'          => 'TEXT',
        'mediumtext'    => 'MEDIUMTEXT',
        'longtext'      => 'LONGTEXT',
        'date'          => 'DATE',
        'datetime'      => 'DATETIME',
        'time'          => 'TIME',
        'timestamp'     => 'TIMESTAMP'
    ];

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        Support::extension('PDO', 'PDO');
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
            $this->connect = new \PDO
            (
                $this->config['dsn'] ?: $this->_dsn($this->config), 
                $this->config['user'], 
                $this->config['password']
            );
        }
        catch( \PDOException $e )
        {
            throw new ConnectionErrorException($e);
        }
        
        if( ! empty($this->config['charset']  ) ) $this->connect->exec("SET NAMES '".$this->config['charset']."'");
        if( ! empty($this->config['charset']  ) ) $this->connect->exec('SET CHARACTER SET '.$this->config['charset']);
        if( ! empty($this->config['collation']) ) $this->connect->exec("SET COLLATION_CONNECTION = '".$this->config['collation']."'");     
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
        $this->query = $this->connect->prepare($query);
        return $this->query->execute($security);
    }

    /**
     * Start Transaction Query
     * 
     * @return bool
     */
    public function transStart()
    {
        return $this->connect->beginTransaction();
    }

    /**
     * Rollback Transaction Query
     * 
     * @return bool
     */
    public function transRollback()
    {
        return $this->connect->rollBack();
    }

    /**
     * Commit Transaction Query
     * 
     * @return bool
     */
    public function transCommit()
    {
        return $this->connect->commit();
    }

    /**
     * Insert Last ID
     * 
     * @return int|false
     */
    public function insertID()
    {
        if( ! empty($this->connect) )
        {
            return $this->connect->lastInsertId('id');
        }
        else
        {
            return false;
        }
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

        for( $i = 0; $i < $numFields; $i++ )
        {
            $field     = $this->query->getColumnMeta($i);
            $fieldName = $field['name'];

            $columns[$fieldName]             = new \stdClass();
            $columns[$fieldName]->name       = $fieldName;
            $columns[$fieldName]->type       = $field['native_type'];
            $columns[$fieldName]->maxLength  = ($field['len'] > 0) ? $field['len'] : NULL;
            $columns[$fieldName]->primaryKey = (int) ( ! empty($field['flags']) && in_array('primary_key', $field['flags'], TRUE));
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
            return $this->query->rowCount();
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
            $meta = $this->query->getColumnMeta($i);

            if( $meta['name'] !== NULL )
            {
                $columns[] = $meta['name'];
            }
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
        if( isset($this->query) )
        {
            return $this->query->columnCount();
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
        if( empty($this->connect) )
        {
            return false;
        }

        return Security\Injection::escapeStringEncode($data);
    }

    /**
     * Returns a string description of the last error.
     * 
     * @return string|false
     */
    public function error()
    {
        if( isset($this->connect) )
        {
            if( ! empty($this->query) )
            {
                $error = $this->query->errorInfo();
            }
            else
            {
                $error = $this->connect->errorInfo();
            }

            return $error[2];
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
            return $this->query->fetch(\PDO::FETCH_BOTH);
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
            return $this->query->fetch(\PDO::FETCH_ASSOC);
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
            return $this->query->fetch();
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
        if( ! empty($this->query) )
        {
            return $this->query->rowCount();
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
        if( isset($this->connect) )
        {
            $this->connect = NULL;
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
            return $this->connect->getAttribute(\PDO::ATTR_SERVER_VERSION);
        }
        else
        {
            return false;
        }
    }

    /**
     * Protected DSN
     * 
     * @param array $config
     * 
     * @return string
     */
    protected function _dsn(Array $config) : String
    {
        $dsn  = 'mysql:';

        $dsn .= ( ! empty($config['host']) )
                ? 'host='.$config['host'].';'
                : '';

        $dsn .= ( ! empty($config['database']) )
                ? 'dbname='.$config['database'].';'
                : '';

        $dsn .= ( ! empty($config['port']) )
                ? 'PORT='.$config['port'].';'
                : '';

        $dsn .= ( ! empty($config['charset']) )
                ? 'charset='.$config['charset']
                : '';

        return rtrim($dsn, ';');
    }
}
