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

use ZN\Base;
use ZN\Config;
use ZN\Support;
use ZN\Datatype;
use ZN\Singleton;

class GrandModel
{
    /**
     * Table name
     * 
     * @var string
     */
    protected $grandTable = '';

    /**
     * Keep connection
     * 
     * @var resource
     */
    protected $connect;

   /**
     * Keep connection for DBTool library
     * 
     * @var resource
     */
    protected $connectTool;

     /**
     * Keep connection for DBForge library
     * 
     * @var resource
     */
    protected $connectForge;

    /**
     * Get list tables
     * 
     * @var array
     */
    protected $tables;

    /**
     * Process status
     * 
     * @var string
     */
    protected $status;

    /**
     * Get query result
     * 
     * @var object
     */
    protected $get;

    /**
     * Keep options
     * 
     * @var array
     */
    protected $options = [];

    /**
     * Table prefix
     * 
     * @var string
     */
    protected $prefix;

    /**
     * String query
     * 
     * @var string
     */
    protected $stringQuery;

    /**
     * Error
     * 
     * @var string
     */
    protected $error;

    /**
     * Magic constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct()
    {
        # Get database connections
        $this->getDatabaseConnections();

        # Get active table name
        $this->setGrandTableName();
    }

    /**
     * Magic call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @param mixed
     */
    public function __call($method, $parameters)
    {
        # If it is a valid transaction type.
        if( $this->isCallColumnTransaction($method, $transaction) )
        {
            return $this->callColumnProcess($method, $parameters, $transaction);
        }
        else
        {
            # 5.3.7[added]|5.6.5[updated]
            return $this->callColumnForge($method, $parameters);
        }

        Support::classMethod(get_called_class(), $method);
    }

    /**
     * Data insert
     * 
     * @param mixed $data = NULL
     * 
     * @return bool
     */
    public function insert($data = NULL) : Bool
    {
        $this->postGetExpression($table, $data);

        return $this->returnQuery($this->connect->insert($table, $data));
    }

    /**
     * Insert csv
     * 
     * @param string $file
     * 
     * @return bool
     */
    public function insertCSV(String $file) : Bool
    {
        return $this->connect->insertCSV($this->grandTable, $file);
    }

    /** 
     * Last insert id
     * 
     * @param void
     * 
     * @return int
     */
    public function insertID() : Int
    {
        return $this->connect->insertID();
    }

    /**
     * Is exists value into table
     * 
     * @param string $column
     * @param string $value
     * 
     * @return bool
     */
    public function isExists(String $column, String $value) : Bool
    {
        return $this->connect->isExists($this->grandTable, $column, $value);
    }

    /**
     * Set select columns
     * 
     * @param string ...$select
     * 
     * @return $this
     */
    public function select(...$select)
    {
        $this->connect->select(...$select);

        return $this;
    }

    /**
     * Update data
     * 
     * @param mixed  $data   = NULL
     * @param string $column = NULL
     * @param string $value  = NULL
     * 
     * @return bool
     */
    public function update($data = NULL, String $column = NULL, String $value = NULL) : Bool
    {
        $this->postGetExpression($table, $data);

        if( $column !== NULL )
        {
            $this->connect->where($column, $value);
        }

        return $this->returnQuery($this->connect->update($table, $data));
    }

    /**
     * Delete data
     * 
     * @param string $column = NULL
     * @param string $value  = NULL
     * 
     * @return bool
     */
    public function delete(String $column = NULL, String $value = NULL) : Bool
    {
        if( $column !== NULL )
        {
            $this->connect->where($column, $value);
        }

        return $this->returnQuery($this->connect->delete($this->grandTable));
    }

    /**
     * Get table object. It is like using DB::get($table)
     * 
     * @return object
     */
    public function get()
    {
        return $this->connect->get($this->grandTable);
    }

    /**
     * protected db get
     * 
     * @param void
     * 
     * @return object
     */
    protected function _get()
    {
        return $this->get = $this->get();
    }

    /**
     * Get columns
     * 
     * @param void
     * 
     * @return array
     */
    public function columns() : Array
    {
        return $this->returnQuery($this->_get()->columns());
    }

    /**
     * Get total columns
     * 
     * @param void
     * 
     * @return int
     */
    public function totalColumns() : Int
    {
        return $this->returnQuery($this->_get()->totalColumns());
    }

    /**
     * Get row
     * 
     * @param mixed $printable = false - options[bool|index]
     * 
     * @return object
     */
    public function row($printable = false)
    {
        return $this->returnQuery($this->_get()->row($printable));
    }

    /**
     * Get result 
     * 
     * @param string $type = 'object' - options[object|array|json]
     */
    public function result(String $type = 'object')
    {
        return $this->returnQuery($this->_get()->result($type));
    }

    /**
     * Increment columns value
     * 
     * @param mixed $columns
     * @param int   $increment = 1
     * 
     * @return bool
     */
    public function increment($columns, Int $increment = 1) : Bool
    {
        return $this->returnQuery($this->connect->increment($this->grandTable, $columns, $increment));
    }

     /**
     * Decrement columns value
     * 
     * @param mixed $columns
     * @param int   $decrement = 1
     * 
     * @return bool
     */
    public function decrement($columns, Int $decrement = 1) : Bool
    {
        return $this->returnQuery($this->connect->decrement($this->grandTable, $columns, $decrement));
    }

    /**
     * Status table
     * 
     * @param string $type = 'row' - options[row|result]
     * 
     * @return mixed
     */
    public function status(String $type = 'row')
    {
        return $this->returnQuery($this->connect->status($this->grandTable)->$type());
    }

    /**
     * Get total rows
     * 
     * @param bool $status = false
     * 
     * @return int
     */
    public function totalRows(Bool $status = false) : Int
    {
        return $this->returnQuery($this->_get()->totalRows($status));
    }

    /**
     * Set where
     * 
     * @param mixed  $column
     * @param string $value   = NULL
     * @param string $logical = NULL
     * 
     * @return $this
     */
    public function where($column, String $value = NULL, String $logical = NULL)
    {
        $this->connect->where($column, $value, $logical);

        return $this;
    }

    /**
     * Set having
     * 
     * @param mixed  $column
     * @param string $value   = NULL
     * @param string $logical = NULL
     * 
     * @return $this
     */
    public function having($column, String $value = NULL, String $logical = NULL)
    {
        $this->connect->having($column, $value, $logical);

        return $this;
    }

    /**
     * Where group
     * 
     * @param mixed ...$args
     * 
     * @return $this
     */
    public function whereGroup(...$args)
    {
        $this->connect->whereGroup(...$args);

        return $this;
    }

    /**
     * Where having
     * 
     * @param mixed ...$args
     * 
     * @return $this
     */
    public function havingGroup(...$args)
    {
        $this->connect->havingGroup(...$args);

        return $this;
    }

    /**
     * Inner join
     * 
     * @param string $tableColumn
     * @param string $otherTableColumn
     * @param string $operator = '='
     * 
     * @return $this
     */
    public function innerJoin(String $table, String $otherColumn, String $operator = '=')
    {
        $this->connect->innerJoin($table, $otherColumn, $operator);

        return $this;
    }

     /**
     * Outer join
     * 
     * @param string $tableColumn
     * @param string $otherTableColumn
     * @param string $operator = '='
     * 
     * @return $this
     */
    public function outerJoin(String $table, String $otherColumn, String $operator = '=')
    {
        $this->connect->outerJoin($table, $otherColumn, $operator);

        return $this;
    }

    /**
     * Left join
     * 
     * @param string $tableColumn
     * @param string $otherTableColumn
     * @param string $operator = '='
     * 
     * @return $this
     */
    public function leftJoin(String $table, String $otherColumn, String $operator = '=')
    {
        $this->connect->leftJoin($table, $otherColumn, $operator);

        return $this;
    }

    /**
     * Right join
     * 
     * @param string $tableColumn
     * @param string $otherTableColumn
     * @param string $operator = '='
     * 
     * @return $this
     */
    public function rightJoin(String $table, String $otherColumn, String $operator = '=')
    {
        $this->connect->rightJoin($table, $otherColumn, $operator);

        return $this;
    }

    /**
     * Join
     * 
     * @param string $table
     * @param string $condition
     * @param string $type = NULL
     * 
     * @return $this
     */
    public function join(String $table, String $condition, String $type = NULL)
    {
        $this->connect->join($table, $condition, $type);

        return $this;
    }

    /**
     * Duplicate check 
     * 
     * @param string ...$columns
     * 
     * @return $this
     */
    public function duplicateCheck(...$args)
    {
        $this->connect->duplicateCheck(...$args);

        return $this;
    }

    /**
     * Duplicate check update
     * 
     * @param string ...$columns
     * 
     * @return $this
     */
    public function duplicateCheckUpdate(...$args)
    {
        $this->connect->duplicateCheckUpdate(...$args);

        return $this;
    }

    /**
     * Set order by
     * 
     * @param mixed  $condition
     * @param string $type = NULL
     * 
     * @return $this
     */
    public function orderBy($condition, String $type = NULL)
    {
        $this->connect->orderBy($condition, $type);

        return $this;
    }

    /**
     * Set group by
     * 
     * @param string ...$args
     * 
     * @return $this
     */
    public function groupBy(...$args)
    {
        $this->connect->groupBy(...$args);

        return $this;
    }

    /**
     * Set limit
     * 
     * @param int $start = 0
     * @param int $limit = 0
     * 
     * @return $this
     */
    public function limit($start = 0, Int $limit = 0)
    {
        $this->connect->limit($start, $limit);

        return $this;
    }

    /**
     * Get pagination
     * 
     * @param string $url      = NULL
     * @param array  $settings = []
     * @param bool   $output   = true
     * 
     * @return mixed
     */
    public function pagination(String $url = NULL, Array $settings = [], Bool $output = true)
    {
        if( ! empty($this->get) )
        {
            $get = $this->get;
        }
        else
        {
            $get = $this->_get();
        }

        return $get->pagination($url, $settings, $output);
    }

    /**
     * Create table
     * 
     * @param mixed $data
     * @param mxeid $extra = NULL
     * 
     * @return bool
     */
    public function create($data = NULL, $extra = NULL) : Bool
    {
        $this->status = 'create';

        if( ! empty($this->options) )
        {
            $extra = $data;
            $data  = $this->options;
            
            $this->options = [];
        }  

        return $this->returnQuery($this->connectForge->createTable($this->grandTable, $data, $extra), 'forge');
    }

    /**
     * Truncate table
     * 
     * @param void
     * 
     * @return bool
     */
    public function truncate() : Bool
    {
        return $this->returnQuery($this->connectForge->truncate($this->grandTable), 'forge');
    }

    /**
     * Add column
     * 
     * @param array $columns
     * 
     * @return bool
     */
    public function add() : Bool
    {
        $columns = $this->options;

        $this->options = [];

        return $this->returnQuery($this->connectForge->addColumn($this->grandTable, $columns), 'forge');
    }

    /**
     * Drop column
     * 
     * @param array $column
     * 
     * @return bool
     */
    public function drop() : Bool
    {
        $columns = $this->options;

        $this->options = [];

        return $this->returnQuery($this->connectForge->dropColumn($this->grandTable, array_keys($columns)), 'forge');
    }
 
    /**
     * Modify column
     * 
     * @param array $columns
     * 
     * @return bool
     */
    public function modify() : Bool
    {
        $columns = $this->options;

        $this->options = [];

        return $this->returnQuery($this->connectForge->modifyColumn($this->grandTable, $columns), 'forge');
    }

    /**
     * Rename column
     * 
     * @param array $column
     * 
     * @return bool
     */
    public function rename() : Bool
    {
        $columns = $this->options;

        $this->options = [];

        return $this->returnQuery($this->connectForge->renameColumn($this->grandTable, $columns), 'forge');
    }

     /**
     * Optimize table
     * 
     * @param void
     * 
     * @return string
     */
    public function optimize() : String
    {
        return $this->returnQuery($this->connectTool->optimizeTables($this->grandTable), 'tool');
    }

    /**
     * Repair table
     * 
     * @param void
     * 
     * @return string
     */
    public function repair() : String
    {
        return $this->returnQuery($this->connectTool->repairTables($this->grandTable), 'tool');
    }

    /**
     * Backup table
     * 
     * @param string $fileName = NULL
     * @param string $path     = STORAGE_DIR
     * 
     * @return string
     */
    public function backup(String $fileName = NULL, String $path = STORAGE_DIR) : String
    {
        return $this->returnQuery($this->connectTool->backup($this->grandTable, $fileName, $path), 'tool');
    }

    /**
     * Get error
     * 
     * @param void
     * 
     * @return mixed
     */
    public function error()
    {
        $error = $this->error ?: false;

        $this->error = NULL;

        return $error;
    }

    /**
     * Get string query
     * 
     * @param void
     * 
     * @return mixed
     */
    public function stringQuery()
    {
        $query = $this->stringQuery ?: false;

        $this->stringQuery = NULL;

        return $query;
    }

    /**
     * Protected get database connections
     */
    protected function getDatabaseConnections()
    {
        $staticConnection    = defined('static::connection') ? static::connection : NULL;

        $this->connect       = Singleton::class('ZN\Database\DB')->differentConnection($staticConnection);
        $this->connectTool   = Singleton::class('ZN\Database\DBTool')->differentConnection($staticConnection);
        $this->connectForge  = Singleton::class('ZN\Database\DBForge')->differentConnection($staticConnection);
        $this->tables        = $this->connectTool->listTables();
        $this->prefix        = $staticConnection['prefix'] ?? Config::database('database')['prefix'];
    }

    /**
     * Protected set grand table name
     */
    protected function setGrandTableName()
    {
        if( defined('static::table') )
        {
            $this->grandTable = static::table;
        }
        else
        {
            $this->grandTable = $this->getGrandTableName();
        }
    }

    /**
     * Protected get grand table name
     */
    protected function getGrandTableName()
    {
        return Base::removeSuffix
        (
            Base::removeSuffix
            (
                Base::removePrefix(Datatype::divide(get_called_class(), '\\', -1), INTERNAL_ACCESS), 
                'Grand'
            ), 
            'Vision'
        );
    }

    /**
     * protected post get expressions
     * 
     * @param string &$table
     * @param array  &$data
     * 
     * @return void
     */
    protected function postGetExpression(&$table, &$data)
    {
        $table = $this->grandTable;

        if( is_string($data) )
        {
            $table = $data . ':' . $table;
            $data  = [];
        }

        $data = $data ?: $this->options;

        $this->options = [];
    }

    /**
     * protected call column process - 5.6.2|5.7.6.6[update]
     * 
     * @param string $method
     * @param array  $params
     * @param string $type
     * 
     * @return mixed
     */
    protected function callColumnProcess($method, $params, $type)
    {
        $func = $type;
        $col  = substr($method, strlen($type));
        $data = NULL;

        if( $func === 'update' )
        {
            # 5.3.7[added]
            if( ! empty($this->options) )
            {
                $params[1] = $params[0];
                $params[0] = $this->options;

                $this->options = [];
            }

            if( ! isset($params[1]) )
            {
                return false;
            }

            return $this->where($col, $params[1])->$func($params[0]);
        }

        return $this->where($col, $params[0])->$func();
    }

    /**
     * Protected call column forge
     */
    protected function callColumnForge($method, $parameters)
    {
        # 5.3.7[added]
        $param = $parameters[0] ?? NULL;

        $this->options[$method] = $param;

        return $this;
    }

    /**
     * Protected is call column process
     */
    protected function isCallColumnTransaction($method, &$selectTransaction)
    {
        $transactions = ['row', 'result', 'update', 'delete'];

        foreach( $transactions as $transaction )
        {
            if( stripos($method, $transaction) === 0 )
            {
                $selectTransaction = $transaction;

                break;
            }
        }

        return $selectTransaction ?? NULL;
    }

    /**
     * protected return query
     */
    protected function returnQuery($process, $fix = '')
    {
        $this->stringQuery = $this->{'connect' . ucfirst($fix)}->stringQuery();
        $this->error       = $this->{'connect' . ucfirst($fix)}->error();

        return $process;
    }
}
