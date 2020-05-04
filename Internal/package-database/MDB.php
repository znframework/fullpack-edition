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

use ZN\Config;
use MongoDB\BSON\Regex;
use MongoDB\Driver\Query;
use MongoDB\Driver\Session;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Command;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\ReadConcern;
use MongoDB\Driver\WriteConcern;
use MongoDB\Driver\ReadPreference;
use MongoDB\Driver\Exception\RuntimeException;
use ZN\Database\Exception\OrderByInvalidSecondArgumentException;

class MDB implements MDBInterface
{
    /**
     * Keeps manager
     * 
     * @var Manager
     */
    protected $manager;

    /**
     * Keeps host
     * 
     * @var string
     */
    protected $host    = '127.0.0.1';

    /**
     * Keeps options
     * 
     * @var array
     */
    protected $options = [];

    /**
     * Keeps executable
     * 
     * @var array
     */
    protected $executable = [];

    /**
     * Keeps filters
     * 
     * @var array
     */
    protected $filters = [];
    
    /**
     * Keeps config
     * 
     * @var array
     */
    protected $config  = [];

    /**
     * Keeps result
     * 
     * @var array
     */
    protected $result;

    /**
     * Keeps error
     * 
     * @var string
     */
    protected $error = '';

    /**
     * Magic constructor method.
     * 
     * @param array $config
     */
    public function __construct($config = [])
    {
        if( $config !== NULL )
        {
            $config = $config ?: Config::get('Database')['mongodb'] ?? [];

            $this->manager  = new Manager
            (
                'mongodb://' . $config['dns'] . '/', 
                $config['options'] ?? [],
                $config['driverOptions'] ?? []
            );

            $this->database = $config['database'] ?? 'test';
        }   
    }

    /**
     * Executable
     * 
     * @param array $options
     */
    public function executable($options)
    {
        $this->executable = $config;

        return $this;
    }

    /**
     * MongoDB\Driver\WriteConcern
     * 
     * @return WriteConcern
     */
    public static function writeConcern(...$parameters)
    {
        return new WriteConcern(...$parameters);
    }

    /**
     * MongoDB\Driver\ReadPreference
     * 
     * @return ReadPreference
     */
    public static function readPreference(...$parameters)
    {
        return new ReadPreference(...$parameters);
    }

    /**
     * MongoDB\Driver\ReadConcern
     * 
     * @return ReadConcern
     */
    public static function readConcern(...$parameters)
    {
        return new ReadConcern(...$parameters);
    }

    /**
     * MongoDB\Driver\Session
     * 
     * @return Session
     */
    public static function session(...$parameters)
    {
        return new Session(...$parameters);
    }

    /**
     * New
     * 
     * @param array $config
     */
    public static function new(Array $config)
    {
        return new self($config);
    }

    /**
     * Insert
     * 
     * @param string $table
     * @param array  $datas
     * 
     * @return bool
     */
    public function insert(String $table, Array $datas) : Bool
    {
        $bulk = new BulkWrite;

        if( is_array($datas[0]) )
        {
            foreach( $datas as $data )
            {
                $this->setAutoIncrement($table, $data);

                $bulk->insert($data);
            }
        }
        else
        {
            $this->setAutoIncrement($table, $datas);

            $bulk->insert($datas);
        }

        return (bool) $this->operation($table, $bulk)->getInsertedCount();
    }

    /**
     * Delete
     * 
     * @param string $table
     * 
     * @return bool
     */
    public function delete(String $table) : Bool
    {
        $bulk = new BulkWrite;

        $bulk->delete($this->filters);

        return (bool) $this->operation($table, $bulk)->getDeletedCount();
    }

    /**
     * Update
     * 
     * @param string $table
     * @param array  $datas
     * 
     * @return bool
     */
    public function update(String $table, Array $datas = []) : Bool
    {
        $bulk = new BulkWrite;

        $this->options['multi'] = true;

        $bulk->update($this->filters, ['$set' => $datas], $this->options);

        return (bool) $this->operation($table, $bulk)->getModifiedCount();
    }

    /**
     * Where Regex
     * 
     * @param string $key
     * @param scalar $value
     * @param string $flags = ''
     * 
     * @return self
     */
    public function whereRegex(String $key, $value, String $flags = '')
    {
        return $this->filter($key, new Regex($value, $flags));
    }

    /**
     * Where
     * 
     * @param string $key
     * @param scalar $value
     * 
     * @return self
     */
    public function where(String $key, $value)
    {
        return $this->filter($key, $value);
    }

    /**
     * Upsert
     * 
     * @return self
     */
    public function upsert()
    {
        return $this->option('upsert', true);
    }

    /**
     * Option
     * 
     * @param string $key
     * @param scalar $value
     * 
     * @return self
     */
    public function option(String $key, $value)
    {
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * Option
     * 
     * @param string $key
     * @param scalar $value
     * 
     * @return self
     */
    public function filter(String $key, $value)
    {
        $this->filters[$key] = $value;

        return $this;
    }

    /**
     * Total Rows
     * 
     * @param int
     */
    public function totalRows() : Int
    {
        return count($this->result());
    }

    /**
     * Limit
     * 
     * Usage - 1
     * 
     * @param int $limit
     * 
     * Usage - 2
     * 
     * @param int $skip
     * @param int $limit
     * 
     * @return self
     */
    public function limit(Int $skip, Int $limit = NULL)
    {
        if( $limit === NULL )
        {
            $this->options['limit'] = $skip;
        }
        else
        {
            $this->options['limit'] = $limit;
            $this->options['skip' ] = $skip;
        }
        
        return $this;
    }

    /**
     * Order By
     * 
     * @param string $column
     * @param string $type = 'asc'
     * 
     * @return self
     */
    public function orderBy(String $column, String $type = 'asc')
    {
        $type  = strtolower($type);
        $types = ['asc' => 1, 'desc' => -1];
        
        if( ! isset($types[$type]) )
        {
            throw new OrderByInvalidSecondArgumentException;
        }

        $this->options['sort'] = [$column => $types[$type]];

        return $this;
    }

    /**
     * Get
     * 
     * @param string $table
     * 
     * @return self
     */
    public function get(String $table)
    {
        $execute = $this->execute($table);

        return (new self(NULL))->complete($execute->toArray());
    }

    /**
     * Result
     * 
     * @return array|object
     */
    public function result()
    {
        return $this->result ?? [];
    }

    /**
     * Row
     * 
     * @param int|bool $printable = 0
     * 
     * @return object|false
     */
    public function row($printable = 0)
    {
        $result = $this->result();

        if( $printable < 0 )
        {
            $index = count($result) + $printable;

            return isset($result[$index]) ? (object) $result[$index] : false;
        }
        else
        {
            if( $printable === true )
            {
                return current($result[0] ?? []);
            }

            return isset($result[$printable]) ? (object) $result[$printable] : false;
        }
    }

    /**
     * Create Index
     * 
     * @param string $table
     * @param array  $indexes
     * 
     * @return bool
     */
    public function createIndex(String $table, Array $indexes) : Bool
    {
        $generateIndexses = [];

        foreach( $indexes as $key => $value )
        {
            $generateIndexses[] = ['name' => $key, 'key'  => [$key => $value], 'ns' => $this->collect($table)];
        }

        $index = $this->executeWriteCommand(['createIndexes' => $table, 'indexes' => $generateIndexses]);

        return ! $this->error = $index->toArray()[0]->note ?? false;
    }

    /**
     * Drop Index
     * 
     * @param string $table
     * @param string $indexName
     * 
     * @return bool
     */
    public function dropIndex(String $table, String $indexName) : Bool
    {
        try 
        {
            $this->executeWriteCommand(['dropIndexes' => $table, 'index' => $indexName]);

            return true;
        }
        catch( RuntimeException $e )
        {
            $this->error = $e->getMessage();

            return false;
        }   
    }

    /**
     * Drop/Truncate
     * 
     * @param string $table
     * 
     * @return bool
     */
    public function drop(String $table) : Bool
    {
        return $this->truncate($table);
    }

    /**
     * Create
     * 
     * @param string $table
     * @param array  $options = []
     * 
     * @return bool
     */
    public function create(String $table, Array $options = []) : Bool
    {
        try 
        {
            $command = ['create' => $table];

            foreach( ['autoIndexId', 'capped', 'flags', 'max', 'maxTimeMS', 'size', 'validationAction', 'validationLevel'] as $option ) 
            {
                if( isset($options[$option]) ) 
                {
                    $command[$option] = $options[$option];
                }
            }

            foreach( ['collation', 'indexOptionDefaults', 'storageEngine', 'validator'] as $option ) 
            {
                if( isset($options[$option]) ) 
                {
                    $command[$option] = (object) $options[$option];
                }
            }

            $drop = $this->executeWriteCommand($command);

            return true;
        }
        catch( RuntimeException $e )
        {
            $this->error = $e->getMessage();

            return false;
        }   
    }

    /**
     * Create Auto Increment
     * 
     * @param string $table
     * @param string $column
     */
    public function createAutoIncrement(String $table, String $column)
    {
        $table = $this->getIndexCollectionName($table);

        if( ! $this->get($table)->row() )
        {
            return $this->insert($table, [$column => 1]);
        }

        return false;   
    }

    /**
     * protected index get index collection name
     */
    protected function getIndexCollectionName(String $table)
    {
        return $table . 'Indexes';
    }

    /**
     * Truncate/Drop
     * 
     * @param string $table
     * 
     * @return bool
     */
    public function truncate(String $table) : Bool
    {
        try 
        {
            $drop = $this->executeWriteCommand(['drop' => $table]);

            if( $this->isAutoIncrement($table) )
            {
                $this->executeWriteCommand(['drop' => $table]);
            }

            return true;
        }
        catch( RuntimeException $e )
        {
            $this->error = $e->getMessage();

            return false;
        }   
    }

    /**
     * Databases
     * 
     * @return array
     */
    public function databases() : Array
    {
        $databases = $this->executeReadCommand('admin', ['listDatabases' => 1]);

        $databases = $databases->toArray();

        $returnDatabases = [];

        foreach( $databases[0]['databases'] as $database )
        {
            $returnDatabases[] = $database['name'];
        }

        return $returnDatabases;
    }

    /**
     * Indexes
     * 
     * @return array
     */
    public function indexes(String $table) : Array
    {
        $indexes = $this->executeReadCommand($this->database, ['listIndexes' => $table]);

        $indexes = $indexes->toArray();

        $returnIndexes = [];

        foreach( $indexes as $index )
        {
            $returnIndexes[] = ['key' => key($index['key']), 'name' => $index['name'], 'value' => current($index['key'])];
        }

        return $returnIndexes;
    }

    /**
     * Tables/Collections
     * 
     * @return array
     */
    public function tables() : Array
    {
        return $this->collections();
    }

    /**
     * Collections/Tables
     * 
     * @return array
     */
    public function collections() : Array
    {
        $collections = $this->executeReadCommand($this->database, ['listCollections' => 1]);

        $collections = $collections->toArray();

        $returnCollections = [];

        foreach( $collections as $collection )
        {
            $returnCollections[] = $collection['name'];
        }

        return $returnCollections;
    }

    /**
     * Error
     * 
     * @return string
     */
    public function error() : String
    {
        return $this->error;
    }

    /**
     * protected is auto increment
     */
    protected function isAutoIncrement(String &$table)
    {
        return in_array($table = $this->getIndexCollectionName($table), $this->collections());
    }

    /**
     * protected set auto increment
     */
    protected function setAutoIncrement(String $table, &$data)
    {
        if( $this->isAutoIncrement($table) )
        {
            $get = $this->get($table);
            
            $row = $get->row();
            
            $id  = key(array_reverse((array) $row));

            $data = array_merge([$id => $get->totalRows()], $data);

            $this->insert($table, [$id => 1]);
        }
    }

    /**
     * protected complete
     */
    protected function complete($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * protected execute read command
     */
    protected function executeReadCommand(String $database, Array $command)
    {
        $query = $this->manager->executeReadCommand($database, new Command($command), $this->executable);

        $query->setTypeMap(['root' => 'array', 'document' => 'array']);

        $this->defaultExecuteReadCommand();

        return $query;
    }

    /**
     * protected execute write command
     */
    protected function executeWriteCommand(Array $command)
    {
        $return = $this->manager->executeWriteCommand($this->database, new Command($command), $this->executable);

        $this->defaultExecuteWriteCommand();
        
        return $return;
    }

    /**
     * protected operation
     */
    protected function operation(String $table, $bulk)
    {
        $return = $this->manager->executeBulkWrite($this->collect($table), $bulk, $this->executable);

        $this->defaultExecute();

        return $return;
    }

    /**
     * protected execute
     */
    protected function execute(String $table)
    {
        $return = $this->manager->executeQuery($this->collect($table), new Query($this->filters, $this->options), $this->executable);

        $this->defaultExecute();

        return $return;
    }

    /**
     * protected collect
     */
    protected function collect(String $collection)
    {
        return $this->database . '.' . $collection;
    }

    /**
     * protected default execute read command
     */
    protected function defaultExecuteReadCommand()
    {
        $this->executable = [];
    }

    /**
     * protected default execute write command
     */
    protected function defaultExecuteWriteCommand()
    {
        $this->options    = [];
        $this->executable = [];
    }

    /**
     * protected default
     */
    protected function defaultExecute()
    {
        $this->filters    = [];
        $this->options    = [];
        $this->executable = [];
    }
}