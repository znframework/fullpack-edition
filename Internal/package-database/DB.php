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
use ZN\Datatype;
use ZN\Singleton;
use ZN\Request\URI;
use ZN\Request\Method;
use ZN\Protection\Json;
use ZN\DataTypes\Arrays;
use ZN\Filesystem\Converter;
use ZN\Database\Exception\UnconditionalException;
use ArrayObject;

#[\AllowDynamicProperties]

class DB extends Connection
{
    /**
     * Vartype Elements
     * 
     * @var array
     */
    protected $vartypeElements =
    [
        'int'     , 'smallint', 'tinyint'   , 'mediumint', 'bigint',
        'decimal' , 'double'  , 'float'     ,
        'char'    , 'varchar' , 
        'tinytext', 'text'    , 'mediumtext', 'longtext' ,
        'date'    , 'time'    , 'timestamp' , 'datetime' ,
        
        'integer' => 'int'
    ];

    /**
     * Statement Elements
     * 
     * @var array
     */
    protected $statementElements =
    [
        'autoincrement', 'primarykey', 'foreignkey', 'unique',
        'null'         , 'notnull'   ,
        'exists'       , 'notexists' ,
        'constraint'
    ];

    /**
     * Function Elements
     * 
     * @var array
     */
    protected $functionElements =
    [
        'ifnull' , 'nullif'      , 'abs'      , 'mod'      , 'asin'     ,
        'acos'   , 'atan'        , 'atan2'    , 'ceil'     , 'ceiling'  ,
        'cos'    , 'cot'         , 'crc32'    , 'degrees'  , 'exp'      ,
        'floor'  , 'ln'          , 'log10'    , 'log2'     , 'log'      ,
        'pi'     , 'pow'         , 'power'    , 'radians'  , 'rand'     ,
        'round'  , 'sign'        , 'sin'      , 'sqrt'     , 'tan'      ,
        'ascii'  , 'field'       , 'format'   , 'lower'    , 'upper'    ,
        'length' , 'ltrim'       , 'substring', 'ord'      , 'position' ,
        'quote'  , 'repeat'      , 'rtrim'    , 'soundex'  , 'space'    ,
        'substr' , 'trim'        , 'ucase'    , 'lcase'    , 'benchmark',
        'charset', 'coercibility', 'user'     , 'collation', 'database' ,
        'schema' , 'avg'         , 'min'      , 'max'      , 'count'    ,
        'sum'    , 'variance'    ,
        'ifelse'         => 'IF'             ,
        'charlength'     => 'CHAR_LENGTH'    ,
        'substringindex' => 'SUBSTRING_INDEX',
        'connectionid'   => 'CONNECTION_ID'  ,
        'currentuser'    => 'CURRENT_USER'   ,
        'lastinsertid'   => 'LAST_INSERT_ID' ,
        'systemuser'     => 'SYSTEM_USER'    ,
        'sessionuser'    => 'SESSION_USER'   ,
        'rowcount'       => 'ROW_COUNT'      ,
        'versioninfo'    => 'VERSION'
    ];

    /*
    |--------------------------------------------------------------------------
    | Scalar Variables
    |--------------------------------------------------------------------------
    |
    | Definitions of scaled of variables.
    |
    */

    private $select     , $where       , $distinct         , $highPriority, $lowPriority  ;
    private $delayed    , $procedure   , $outFile          , $characterSet, $into         ;
    private $forUpdate  , $quick       , $ignore           , $partition   , $straightJoin ;
    private $smallResult, $bigResult   , $bufferResult     , $cache       , $calcFoundRows;
    private $groupBy    , $having      , $orderBy          , $limit       , $join         ;
    private $transStart , $transError  , $duplicateCheck   , $duplicateCheckUpdate        ;
    private $joinType   , $joinTable   , $unionQuery = NULL, $caching = [], $jsonDecode   ;
    private $hashId     , $hashIdColumn, $isUpdate = false , $unset   = [], $object       ;
    private $paging     , $from        , $returnQueryType  , $results;

    /**
     * Callable talking queries.
     */
    use CallableTalkingQueries;

    /**
     * Defines SQL SELECT
     * 
     * @param string ...$condition
     * 
     * @return DB
     */
    public function select(...$condition) : DB
    {
        if( empty($condition[0]) )
        {
            $condition[0] = '*';
        }

        $condition = rtrim(implode(',', array_map(function($value)
        { 
            return preg_replace_callback('/(?<database>\w+\.)*(?<table>\w+\.\w+)/', function($data)
            {
                return $data['database'] . $this->prefix . $data['table'];
            }, $value);
            
        }, $condition)), ',');

        $this->select = ' '.$condition.' ';

        return $this;
    }

    /**
     * Defines SQL WHERE 
     * 
     * @param mixed  $column
     * @param string $value   = NULL
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function where($column, $value = NULL, string $logical = NULL) : DB
    {
        $this->buildWhereHavingClause($column, $value, $logical, __FUNCTION__);

        return $this;
    }

    /**
     * Defines SQL WHERE 
     * 
     * @param mixed  $column
     * @param string $value   = NULL
     * 
     * @return DB
     */
    public function whereAnd($column, $value = NULL) : DB
    {
        $this->where($column, $value, 'AND');

        return $this;
    }

    /**
     * Defines SQL WHERE 
     * 
     * @param mixed  $column
     * @param string $value   = NULL
     * 
     * @return DB
     */
    public function whereOr($column, $value = NULL) : DB
    {
        $this->where($column, $value, 'OR');

        return $this;
    }

    /**
     * WHERE NULL OR EMPTY
     * 
     * @param mixed  $column
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereEmpty($column, string $logical = NULL) : DB
    {
        $group[] = ['exp:' . $column, '""', 'or'];
        $group[] = ['exp:' . $column . ' is', 'null'];

        if( $logical !== NULL )
        {
            $group[] = $logical;
        }

        $this->whereGroup(...$group);

        return $this;
    }

    /**
     * WHERE NOT [NULL AND EMPTY]
     * 
     * @param mixed  $column
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereNotEmpty($column, string $logical = NULL) : DB
    {
        $group[] = ['exp:' . $column . ' !=', '""', 'and'];
        $group[] = ['exp:' . $column . ' is not', 'null'];

        if( $logical !== NULL )
        {
            $group[] = $logical;
        }

        $this->whereGroup(...$group);

        return $this;
    }

    /**
     * WHERE NULL
     * 
     * @param mixed  $column
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereNull($column, string $logical = NULL) : DB
    {
        $this->where('exp:' . $column . ' is', 'null', $logical);

        return $this;
    }

    /**
     * WHERE NOT NULL
     * 
     * @param mixed  $column
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereNotNull($column, string $logical = NULL) : DB
    {
        $this->where('exp:' . $column . ' is', 'not null', $logical);

        return $this;
    }

     /**
     * Defines SQL WHERE 
     * 
     * @param mixed  $column
     * @param string $value   = NULL
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereNot($column, $value = NULL, string $logical = NULL) : DB
    {
        $this->where($column . ' != ', $value, $logical);

        return $this;
    }

    /**
     * Defines SQL WHERE JSON_SEARCH IS NOT NULL
     * 
     * @param mixed  $column
     * @param string $value   = NULL
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereJson($column, $value = NULL, string $logical = NULL) : DB
    {
        $this->specialDefinedWhere($column, $value, $logical, __FUNCTION__);

        return $this;
    }

    /**
     * Defines SQL WHERE JSON_SEARCH IS NULL
     * 
     * @param mixed  $column
     * @param string $value   = NULL
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereNotJson($column, $value = NULL, string $logical = NULL) : DB
    {
        $this->specialDefinedWhere($column, $value, $logical, __FUNCTION__);

        return $this;
    }

    /**
     * Full Text
     * 
     * 5.7.4[added]
     * 
     * @param string $column
     * @param string $value
     * @param string $type = NULL
     * 
     * @return string
     */
    public function whereFullText($column, $value = NULL, string $type = NULL, string $logical = NULL) : DB
    {
        $this->where('exp:' . $this->db->fullText($column, $this->escapeStringAddNail($value), $type), '', $logical);

        return $this;
    }

    /**
     * Defines SQL WHERE BETWEEN value1 and value2
     * 
     * @param mixed  $column
     * @param string $value1  = NULL
     * @param string $value2  = NULL
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereBetween($column, string $value1 = NULL, string $value2 = NULL, string $logical = NULL) : DB
    {
        $this->where($column . ' between', $this->between($value1, $value2), $logical);

        return $this;
    }

    /**
     * Defines SQL WHERE LIKE %value%
     * 
     * @param mixed  $column
     * @param string $value   = NULL
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereLike($column, $value = NULL, string $logical = NULL) : DB
    {
        $this->where($column . ' like', $this->like($value, 'inside'), $logical);

        return $this;
    }

    /**
     * Defines SQL WHERE LIKE value%
     * 
     * @param mixed  $column
     * @param string $value   = NULL
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereStartLike($column, $value = NULL, string $logical = NULL) : DB
    {
        $this->where($column . ' like', $this->like($value, 'starting'), $logical);

        return $this;
    }

    /**
     * Defines SQL WHERE LIKE %value
     * 
     * @param mixed  $column
     * @param string $value   = NULL
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereEndLike($column, $value = NULL, string $logical = NULL) : DB
    {
        $this->where($column . ' like', $this->like($value, 'ending'), $logical);

        return $this;
    }

    /**
     * Defines SQL WHERE IN(...$values)
     * 
     * @param mixed  $column
     * @param array  $values  = []
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereIn($column, array $values = [], string $logical = NULL) : DB
    {
        $this->where($column . ' in', $this->in(...$values), $logical);

        return $this;
    }

    /**
     * Defines SQL WHERE NOT IN(...$values)
     * 
     * @param mixed  $column
     * @param array  $values  = []
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereNotIn($column, array $values = [], string $logical = NULL) : DB
    {
        $this->where($column . ' not in', $this->notIn(...$values), $logical);

        return $this;
    }

    /**
     * Defines SQL WHERE IN(SELECT * FROM $table)
     * 
     * @param mixed  $column
     * @param string $table
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereInTable($column, string $table, string $logical = NULL) : DB
    {
        $this->where($column . ' in', $this->inTable($table), $logical);

        return $this;
    }

    /**
     * Defines SQL WHERE IN(SELECT * FROM $table)
     * 
     * @param mixed  $column
     * @param string $table
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function whereInQuery($column, string $table, string $logical = NULL) : DB
    {
        $this->where($column . ' in', $this->inQuery($table), $logical);

        return $this;
    }

    /**
     * Defines SQL WHERE 
     * 
     * @param array ...$args
     * 
     * @return DB
     */
    public function whereGroup(...$args) : DB
    {
        $this->where .= $this->whereHavingGroup($args);

        return $this;
    }

    /** 
     * Having Group
     * 
     * @param array ...$args
     * 
     * @return DB
     */
    public function havingGroup(...$args) : DB
    {
        $this->having .= $this->whereHavingGroup($args);

        return $this;
    }

    /**
     * Defines SQL HAVING 
     * 
     * @param mixed  $column
     * @param string $value   = NULL
     * @param string $logical = NULL
     * 
     * @return DB
     */
    public function having($column, $value = NULL, string $logical = NULL) : DB
    {
        $this->buildWhereHavingClause($column, $value, $logical, __FUNCTION__);

        return $this;
    }

    /**
     * Caching Query
     * 
     * @param string $time
     * @param string $driver = NULL
     * 
     * @return DB
     */
    public function caching($time, string $driver = NULL) : DB
    {
        $this->caching['time']   = $time;
        $this->caching['driver'] = $driver ?? $this->config['cacheDriver'] ?? 'file';

        return $this;
    }

    /**
     * Clean Cache
     * 
     * @param string $driver = 'file'
     * 
     * @return bool
     */
    public function cleanCaching(string $driver = 'file') : bool
    {
        return Singleton::class('ZN\Cache\Processor')->driver($this->caching['driver'] ?? $driver)->delete($this->getEncryptedCacheQuery());
    }

    /**
     * Join table
     * 
     * @param string $table
     * @param string $condition
     * @param string $type = NULL
     * 
     * @return DB
     */
    public function join(string $table, string $condition, string $type = NULL) : DB
    {
        $tableEx = explode('.', $table);

        switch( count($tableEx) )
        {
            case 2:
                $table = $tableEx[0] . '.' . $this->prefix . $tableEx[1];
            break;

            case 1:
                $table   = $this->prefix.$table;
            break;
        }

        $type = strtoupper((string) $type);

        $this->joinType  = $type;
        $this->joinTable = $table;

        $this->join .= ' '.$type.' JOIN '.$table.' ON '.$condition.' ';

        return $this;
    }

    /**
     * Inner Join
     * 
     * @param string $mainTableAndColumn
     * @param string $otherTableAndColumn
     * @param string $operator = '='
     * 
     * @return DB
     */
    public function innerJoin(string $table, string $otherColumn, string $operator = '=') : DB
    {
        $this->buildJoinClause($table, $otherColumn, $operator, 'INNER');

        return $this;
    }

    /**
     * Outer Join
     * 
     * @param string $mainTableAndColumn
     * @param string $otherTableAndColumn
     * @param string $operator = '='
     * 
     * @return DB
     */
    public function outerJoin(string $table, string $otherColumn, string $operator = '=') : DB
    {
        $this->buildJoinClause($table, $otherColumn, $operator, 'FULL OUTER');

        return $this;
    }

    /**
     * Left Join
     * 
     * @param string $mainTableAndColumn
     * @param string $otherTableAndColumn
     * @param string $operator = '='
     * 
     * @return DB
     */
    public function leftJoin(string $table, string $otherColumn, string $operator = '=') : DB
    {
        $this->buildJoinClause($table, $otherColumn, $operator, 'LEFT');

        return $this;
    }

    /**
     * Right Join
     * 
     * @param string $mainTableAndColumn
     * @param string $otherTableAndColumn
     * @param string $operator = '='
     * 
     * @return DB
     */
    public function rightJoin(string $table, string $otherColumn, string $operator = '=') : DB
    {
        $this->buildJoinClause($table, $otherColumn, $operator, 'RIGHT');

        return $this;
    }

    /**
     * Group By
     * 
     * @param string ...$args
     * 
     * @return DB
     */
    public function groupBy(...$args) : DB
    {
        $this->groupBy .= implode(',', $args).', ';

        return $this;
    }

    /**
     * Order By
     * 
     * @param mixed  $condition
     * @param string $type = NULL
     * 
     * @return DB
     */
    public function orderBy($condition, string $type = NULL) : DB
    {
        if( is_string($condition) )
        {
            $this->orderBy .= $condition.' '.$type.', ';
        }
        else
        {
            if( ! empty($condition) ) foreach( $condition as $key => $val )
            {
                $this->orderBy .= $key.' '.$val.', ';
            }
        }

        return $this;
    }

    /**
     * Order By Field
     * 
     * @param string $field
     * @param array  $values
     * 
     * @return DB
     */
    public function orderByField(string $field, array $values) : DB
    {
        $revalues = [];

        foreach( $values as $value )
        {
            $revalues[] = $this->escapeStringAddNail($value ?? '', true);
        }

        return $this->orderBy('FIELD(' . $field . ', ' . implode(', ', $revalues) . ')');
    }

    /**
     * Order By Random
     * 
     * @return DB
     */
    public function orderByRandom() : DB
    {
        return $this->orderBy('rand()');
    }

    /**
     * Limit
     * 
     * @param int $start = NULL
     * @param int $limit = 0
     * 
     * @return DB
     */
    public function limit($start = NULL, int $limit = 0) : DB
    {
        if( $start < 0 )
        {
            $start = $this->paging(is_numeric($segment = URI::segment($start)) ? $segment : 1, $limit);

            $this->paging = 'page';
        }
        else if( $start === NULL )
        {
            $start = URI::segment(-1);
        }

        $start = (int) $start;

        $this->limit = $this->db->limit($start, $limit);
        
        return $this;
    }

    /**
     * protected paging
     */
    protected function paging($start, $limit)
    {
        if( $start == 0 )
        {
            return 1; // @codeCoverageIgnore
        }

        return ($start - 1) * $limit;
    }

    /**
     * Basic Query
     * 
     * @return DB
     */
    public function basic() : DB
    {
        $this->returnQueryType = 'basicQuery';

        return $this;
    }

    /**
     * Get Table
     * 
     * @param string $table  = NULL
     * @param string $return = 'object' - Options['object'|'array'|'json']
     * 
     * @return mixed
     */
    public function get(string $table = NULL, string $return = 'object')
    {
        $this->tableName = $table = $this->addPrefixForTableAndColumn($table, 'table');
     
        $finalQuery =     'SELECT '         . 
                          $this->distinct   . $this->highPriority . $this->straightJoin . 
                          $this->smallResult. $this->bigResult    . $this->bufferResult . 
                          $this->cache      . $this->calcFoundRows. $this->buildSelectClause()    .
                          ' FROM '          . 
                          $table.' '        . $this->partition    . $this->join         . 
                          $this->buildWhereClause()   . $this->buildGroupByClause()   . $this->buildHavingClause()    . 
                          $this->buildOrderByClause() . $this->limit        . $this->procedure    . 
                          $this->outFile    . $this->characterSet . $this->into         .
                          $this->forUpdate;

        if( $this->unionQuery !== NULL )
        {
            $finalQuery       = $this->unionQuery . ' ' . $finalQuery;
            $this->unionQuery = NULL;
        }

        $returnQuery = $this->returnQueryType ?? 'query';

        $isString = $this->string === true || $return === 'string';

        $finalQuery = $this->querySecurity($finalQuery, $isString);

        $this->resetSelectQueryVariables();

        if( $isString )
        {
            $this->string = NULL;

            return $finalQuery;
        }

        if( $this->transaction === true )
        {
            $this->transactionQueries[] = $finalQuery;

            return $this;
        }

        return $this->$returnQuery($finalQuery, $this->secure);
    }

    /**
     * Duplicate Check
     * 
     * @param string ...$args
     * 
     * @return DB
     */
    public function duplicateCheck(...$args) : DB
    {
        $this->duplicateCheck = $args;

        if( empty($this->duplicateCheck) )
        {
            $this->duplicateCheck[0] = '*';
        }

        return $this;
    }

    /**
     * Duplicate Check Update
     * 
     * @param string ...$args
     * 
     * @return DB
     */
    public function duplicateCheckUpdate(...$args) : DB
    {
        $this->duplicateCheck(...$args);

        $this->duplicateCheckUpdate = true;

        return $this;
    }

    /**
     * Real Escape String 
     * 
     * @param string $data
     * 
     * @return string
     */
    public function escapeString(string $data) : string
    {
        return $this->db->realEscapeString($data);
    }

    /**
     * Real Escape String 
     * 
     * @param string $data
     * 
     * @return string
     */
    public function realEscapeString(string $data) : string
    {
        return $this->db->realEscapeString($data);
    }

    /**
     * Get String Query
     * 
     * @param string $table = NULL
     * 
     * @return string
     */
    public function getString(string $table = NULL) : string
    {
        return $this->get($table, 'string');
    }

    /**
     * Set aliases
     * 
     * @param array $aliases
     * 
     * @return self
     */
    public function aliases(array $aliases)
    {
        $this->aliases = $aliases;

        return $this;
    }

    /**
     * Alias
     * 
     * @param string $string
     * @param string $alias
     * @param bool   $brackets = false
     * 
     * @return string
     */
    public function alias(string $string, string $alias, bool $brackets = false) : string
    {
        if( $brackets === true)
        {
            $string = $this->brackets($string);
        }

        return $string.' AS '.$alias;
    }

    /**
     * Brackets
     * 
     * @param string $string
     * 
     * @return string
     */
    public function brackets(string $string) : string
    {
        return ' ( '.$string.' ) ';
    }

    /**
     * Defines SQL ALL
     * 
     * @return DB
     */
    public function all() : DB
    {
        $this->distinct = ' ALL ';
        return $this;
    }

    /**
     * Defines SQL DISTINCT
     * 
     * @return DB
     */
    public function distinct() : DB
    {
        $this->distinct = ' DISTINCT ';
        return $this;
    }

    /**
     * Defines SQL DISTINCTROW
     * 
     * @return DB
     */
    public function distinctRow() : DB
    {
        $this->distinct = ' DISTINCTROW ';
        return $this;
    }

    /**
     * Defines SQL STRAIGHT_JOIN
     * 
     * @return DB
     */
    public function straightJoin() : DB
    {
        $this->straightJoin = ' STRAIGHT_JOIN ';
        return $this;
    }

    /**
     * Defines SQL HIGH_PRIORITY
     * 
     * @return DB
     */
    public function highPriority() : DB
    {
        $this->highPriority = ' HIGH_PRIORITY ';
        return $this;
    }

    /**
     * Defines SQL LOW_PRIORITY
     * 
     * @return DB
     */
    public function lowPriority() : DB
    {
        $this->lowPriority = ' LOW_PRIORITY ';
        return $this;
    }

    /**
     * Defines SQL QUICK
     * 
     * @return DB
     */
    public function quick() : DB
    {
        $this->quick = ' QUICK ';
        return $this;
    }

    /**
     * Defines SQL DELAYED
     * 
     * @return DB
     */
    public function delayed() : DB
    {
        $this->delayed = ' DELAYED ';
        return $this;
    }

    /**
     * Defines SQL IGNORE
     * 
     * @return DB
     */
    public function ignore() : DB
    {
        $this->ignore = ' IGNORE ';
        return $this;
    }

    /**
     * Defines SQL PARTITION
     * 
     * @param string ...$args
     * 
     * @return DB
     */
    public function partition(...$args) : DB
    {
        $this->partition = $this->setMathFunction(__FUNCTION__, $args)->args;
        return $this;
    }

    /**
     * Defines SQL PROCEDURE
     * 
     * @param string ...$args
     * 
     * @return DB
     */
    public function procedure(...$args) : DB
    {
        $this->procedure = $this->setMathFunction(__FUNCTION__, $args)->args;
        return $this;
    }

    /**
     * Defines SQL INTO OUTFILE
     * 
     * @param string $file
     * 
     * @return DB
     */
    public function outFile(string $file) : DB
    {
        $this->outFile = 'INTO OUTFILE '."'".$file."'".' ';
        return $this;
    }

    /**
     * Defines SQL INTO DUMPFILE
     * 
     * @param string $file
     * 
     * @return DB
     */
    public function dumpFile(string $file) : DB
    {
        $this->into = 'INTO DUMPFILE '."'".$file."'".' ';

        return $this;
    }

    /**
     * Defines SQL REFERENCES table(column)
     * 
     * 5.7.4[added]
     * 
     * @return string
     */
    public function references(string $table, string $column) : string
    {
        return $this->db->references($table, $column);
    }

    /**
     * Foreign Key
     * 
     * 5.7.4[added]
     * 
     * @param string $column 
     * @param string $references
     * 
     * @return string
     */
    public function foreignKey($column = NULL, $references = NULL) : string
    {
        return $this->db->foreignKey($column, $references);
    }

    /**
     * Defines SQL CHARACTER SET
     * 
     * @param string $set
     * @param bool   $return = false
     * 
     * @return mixed
     */
    public function characterSet(string $set, bool $return = false)
    {
        $string = 'CHARACTER SET '.$set.' ';

        if( $return === false )
        {
            $this->characterSet = $string;
            return $this;
        }
        else
        {
            return $string;
        }
    }

    /**
     * Character Set
     * 
     * @param string $set
     * 
     * @return string
     */
    public function cset(string $set) : string
    {
        if( empty($set) )
        {
            $set = $this->config['charset'];
        }

        return $this->characterSet($set, true);
    }

    /**
     * Defines SQL COLLATE
     * 
     * @param string $set
     * 
     * @return string
     */
    public function collate(string $set = NULL) : string
    {
        if( empty($set) )
        {
            $set = $this->config['collation'];
        }

        return 'COLLATE '.$set.' ';
    }


    /**
     * Sets encoding
     * 
     * @param string $charset = 'utf8'
     * @param string $collate = 'utf8_general_ci'
     * 
     * @return string
     */
    public function encoding(string $charset = 'utf8', string $collate = 'utf8_general_ci') : string
    {
        $encoding  = $this->cset($charset);
        $encoding .= $this->collate($collate);

        return $encoding;
    }

    /**
     * Defines SQL INTO
     * 
     * @param string $varname1
     * @param string $varname2
     * 
     * @return DB
     */
    public function into(string $varname1, string $varname2 = NULL) : DB
    {
        $this->into = 'INTO '.$varname1.' ';

        if( ! empty($varname2) )
        {
            $this->into .= ', '.$varname2.' ';
        }

        return $this;
    }

    /**
     * Defines SQL FOR UPDATE
     * 
     * @return DB
     */
    public function forUpdate() : DB
    {
        $this->forUpdate = ' FOR UPDATE ';

        return $this;
    }

    /**
     * Defines SQL LOCK IN SHARE MODE
     * 
     * @return DB
     */
    public function lockInShareMode() : DB
    {
        $this->forUpdate = ' LOCK IN SHARE MODE ';

        return $this;
    }

    /**
     * Defines SQL SQL_SMALL_RESULT
     * 
     * @return DB
     */
    public function smallResult() : DB
    {
        $this->smallResult = ' SQL_SMALL_RESULT ';

        return $this;
    }

    /**
     * Defines SQL SQL_BIG_RESULT
     * 
     * @return DB
     */
    public function bigResult() : DB
    {
        $this->bigResult = ' SQL_BIG_RESULT ';

        return $this;
    }

    /**
     * Defines SQL SQL_BUFFER_RESULT
     * 
     * @return DB
     */
    public function bufferResult() : DB
    {
        $this->bufferResult = ' SQL_BUFFER_RESULT ';

        return $this;
    }

    /**
     * Defines SQL SQL_CACHE
     * 
     * @return DB
     */
    public function cache() : DB
    {
        $this->cache = ' SQL_CACHE ';

        return $this;
    }

    /**
     * Defines SQL SQL_NO_CACHE
     * 
     * @return DB
     */
    public function noCache() : DB
    {
        $this->cache = ' SQL_NO_CACHE ';

        return $this;
    }

    /**
     * Defines SQL SQL_CALC_FOUND_ROWS
     * 
     * @return DB
     */
    public function calcFoundRows() : DB
    {
        $this->calcFoundRows = ' SQL_CALC_FOUND_ROWS ';

        return $this;
    }

    /**
     * Defines SQL UNION
     * 
     * @param string $table = NULL
     * 
     * @return DB
     */
    public function union(string $table = NULL, $name = 'UNION') : DB
    {
        $this->unionQuery .= $this->get($table, 'string') . $name;

        return $this;
    }

    /**
     * Defines SQL UNION ALL
     * 
     * @param string $table = NULL
     * 
     * @return DB
     */
    public function unionAll(string $table = NULL) : DB
    {
        $this->union($table, 'UNION ALL');

        return $this;
    }

    /**
     * Json columns decoder
     * 
     * @param variadic $columns
     * 
     * @return DB
     */
    public function jsonDecode(...$columns)
    {
        $this->jsonDecode = ! empty($columns) ? $columns : '*';

        return $this;
    }

    /**
     * Basic Query
     * 
     * @param string $query
     * @param array  $secure = []
     * 
     * @return DB
     */
    public function query(string $query, array $secure = [])
    {
        $secure     = $this->secure ?: $secure; $this->secure     = [];    
        $caching    = $this->caching;           $this->caching    = [];
        $tableName  = $this->tableName;         $this->tableName  = '';
        $jsonDecode = $this->jsonDecode;        $this->jsonDecode = [];
        $paging     = $this->paging ?? 'row';   $this->paging     = NULL;

        return (new self($this->config))->setQueryByDriver($query, $secure, 
        [
            'caching'    => $caching, 
            'tableName'  => $tableName,
            'jsonDecode' => $jsonDecode,
            'paging'     => $paging
        ]);
    }

    /**
     * Exec Basic Query
     * 
     * @param string $query
     * @param array  $secure = []
     * 
     * @return bool
     */
    public function execQuery(string $query, array $secure = []) : bool
    {
        $this->secure = $this->secure ?: $secure;

        return $this->db->exec($this->querySecurity($query), $this->secure);
    }

    /**
     * Basic Query
     * 
     * @param string $query
     * @param array  $secure = []
     * 
     * @return DB
     */
    public function basicQuery(string $query, array $secure = []) : DB
    {
        return $this->setQueryByDriver($query, $secure);
    }

    /**
     * Trans Query
     * 
     * @param string $query
     * @param array  $secure = []
     * 
     * @return DB
     */
    public function transQuery(string $query, array $secure = []) : DB
    {
        return $this->setQueryByDriver($query, $secure);
    }

    /**
     * Multi Query
     * 
     * @param string $query
     * @param array  $secure = []
     * 
     * @return bool
     */
    public function multiQuery(string $query, array $secure = []) : bool
    {
        $this->secure = $this->secure ?: $secure;

        return $this->db->multiQuery($this->querySecurity($query), $this->secure);
    }

    /**
     * Start transaction query
     * 
     * @return DB
     */
    public function transStart() : DB
    {
        $this->transStart = $this->db->transStart();

        $this->transaction = true;

        return $this;
    }

    /**
     * End transaction query
     * 
     * @return bool
     */
    public function transEnd()
    {
        $this->runTransactionQueries();

        if( ! empty($this->transError) )
        {
            $this->db->transRollback();
        }
        else
        {
            $this->db->transCommit();
        }

        $status = ! (bool) $this->transError;

        $this->resetTransactionQueryVariables();

        return $status;
    }

    /**
     * Transaction queryies builder
     * 
     * @param callback $callback
     * 
     * @return bool
     */
    public function transaction($callback) : bool
    {
        $this->transStart();

        $this->transaction = true;

        $callback();

        $this->runTransactionQueries();

        return $this->transEnd();
    }

    /**
     * Get Insert ID
     * 
     * @return int|string
     */
    public function insertID()
    {
        return $this->db->insertId();
    }

    /**
     * Set postgres driver returning id
     * 
     * @param string $returningId
     * 
     * @return self
     */
    public function returningId(string $returningId)
    {
        Properties::$returningId = $returningId;

        return $this;
    }

    /**
     * Get table status
     * 
     * @param string $table = NULL
     * 
     * @return DB
     */
    public function status(string $table = NULL) : DB
    {
        $table = Base::presuffix($this->addPrefixForTableAndColumn($table), "'");

        $query = "SHOW TABLE STATUS FROM " . $this->config['database'] . " LIKE $table";

        $this->runQuery($query);

        return $this;
    }

    /**
     * Increment
     * 
     * @param string $table     = NULL
     * @param mixed  $columns   = []
     * @param int    $increment = 1
     * 
     * @return bool
     */
    public function increment(string $table = NULL, $columns = [], int $increment = 1)
    {
        return $this->setIncrementDecrement($table, $columns, $increment, 'increment');
    }

    /**
     * Decrement
     * 
     * @param string $table     = NULL
     * @param mixed  $columns   = []
     * @param int    $decrement = 1
     * 
     * @return bool
     */
    public function decrement(string $table = NULL, $columns = [], int $decrement = 1)
    {
        return $this->setIncrementDecrement($table, $columns, $decrement, 'decrement');
    }

    /**
     * Insert CSV
     * 
     * @param string $table
     * @param string $file
     * 
     * @return bool
     */
    public function insertCSV(string $table, string $file) : bool
    {
        $this->convertCSVData($file);
        
        array_map(function($data) use($table)
        {
            $this->duplicateCheck()->insert(Base::prefix($table, 'ignore:'), $data);
            
        }, $file);
        
        return true;
    }

    /**
     * Insert 
     * 
     * @param string $table = NULL
     * @param array  $datas = []
     * 
     * @return bool
     */
    public function insert(string $table = NULL, array $datas = [])
    {
        if( isset($datas[0]) && is_array($datas[0]) )
        {
            $insertQuery = $this->multiInsert($table, $datas);
        }
        else
        {
            $this->isHashIdColumn($datas);

            $this->ignoreData($table, $datas);

            $datas = $this->addPrefixForTableAndColumn($datas, 'column');
            
            $this->buildDataValuesQueryForInsert($datas, $data, $values, $duplicateCheckWhere);
            
            $this->duplicateCheckProcess($duplicateCheckWhere, $table, $datas, $return);

            if( isset($return) )
            {
                return $return;
            }
    
            $insertQuery = $this->buildInsertQuery($table, $data, $values);
        }
        
        $this->resetInsertQueryVariables();

        if( $return = $this->runQuery($insertQuery) )
        {
            if( is_string($this->object) )
            {
                Properties::$returningId = $processColumn = $this->object; $this->object = NULL;

                $insertId = $this->insertId(); Properties::$returningId = 'id';

                return $this->where($processColumn, $insertId)->get($table)->row();
            }
        }

        return $return;
    }

    /**
     * Object
     * 
     * @param string $processColumn = 'id'
     * 
     * @return self
     */
    public function object(string $processColumn = 'id')
    {
        $this->object = $processColumn;

        return $this;
    }

    /**
     * Unset
     * 
     * @param variadic ...$argument
     * 
     * @return self
     */
    public function unset(...$columns)
    {
        $this->unset = $columns;

        return $this;
    }

    /**
     * Is update
     *
     * @return boolean
     */
    public function isUpdate() : bool
    {
        return $this->isUpdate;
    }

    /**
     * Set hash id column
     * 
     * @param string $column
     * 
     * @return self
     */
    public function hashIdColumn(string $column)
    {
        $this->hashIdColumn = $column;

        return $this;
    }

    /**
     * Create hash id
     * 
     * @param array $data
     * 
     * @return string
     */
    public function createHashId(array $data) : string
    {
        $output = '';

        foreach( $data as $value )
        {
            $output .= json_encode($value);
        }

        $output .= microtime();

        return md5($output);
    }

    /**
     * Get hash Id 
     * 
     * @return string
     */
    public function hashId()
    {
        return $this->hashId;
    }

    /**
     * Update 
     * 
     * @param string $table = NULL
     * @param array  $datas = []
     * 
     * @return bool
     */
    public function update(string $table = NULL, array $datas = [])
    {
        $this->ignoreData($table, $datas);

        $datas = $this->addPrefixForTableAndColumn($datas, 'column');
        
        $this->buildDataValuesQueryForUpdate($datas, $data);

        $updateQuery = $this->buildUpdateQuery($table, $data, $where);

        $this->resetUpdateQueryVariables();

        if( $return = $this->runQuery($updateQuery) )
        {
            if( $this->object )
            {
                $this->object = NULL;

                return $this->where('exp:' . str_ireplace('WHERE ', '', $where))->get($table)->row(-1);
            }
        }

        return $return;
    }

    /**
     * Delete 
     * 
     * @param string $table = NULL
     * 
     * @return bool
     */
    public function delete(string $table = NULL)
    {
        if( empty($this->where) )
        {
            throw new UnconditionalException();
        }

        $deleteQuery = $this->buildDeleteQuery($table);

        $this->resetDeleteQueryVariables();

        return $this->runQuery($deleteQuery);
    }

    /**
     * Get total rows
     * 
     * @param bool $real = false
     * 
     * @return int
     */
    public function totalRows(bool $total = false) : int
    {
        if( $total === true )
        {
            return $this->query($this->db->cleanLimit($this->stringQuery()))->totalRows();
        }

        return $this->db->numRows();
    }

    /**
     * Get total columns
     * 
     * @return int
     */
    public function totalColumns() : int
    {
        return $this->db->numFields();
    }

    /**
     * Get columns
     * 
     * @return array
     */
    public function columns() : array
    {
        return $this->db->columns();
    }

    /**
     * Get table result
     * 
     * @param string $type = 'objects' - Options[object|array|json]
     * 
     * @return mixed
     */
    public function result(string $type = 'object', $usageRow = false)
    {
        $this->getCacheResult($type, $results);

        if( empty($results) )
        {
            $results = $this->results = ($this->results ?? $this->db->result($type, $this->jsonDecode ?? NULL, $usageRow));
        }

        if( $type === 'json' )
        {
            return Json::encode($results);
        }

        return $results;
    }

    /**
     * Get result json
     * 
     * @return string
     */
    public function resultJson() : string
    {
        return $this->result('json');
    }

    /**
     * Get result array
     * 
     * @return array
     */
    public function resultArray() : array
    {
        return $this->result('array');
    }

    /**
     * Get fetch array
     * 
     * @return array
     */
    public function fetchArray() : array
    {
        return $this->db->fetchArray();
    }

    /**
     * Get fetch assoc
     * 
     * @return array
     */
    public function fetchAssoc() : array
    {
        return $this->db->fetchAssoc();
    }

    /**
     * Get fetch array
     * 
     * @param string $type = 'assoc' - Options[assoc|array|row]
     * 
     * @return array
     */
    public function fetch(string $type = 'assoc') : array
    {
        if( $type === 'assoc' )
        {
            return $this->db->fetchAssoc();
        }
        elseif( $type === 'array')
        {
            return $this->db->fetchArray();
        }
        else
        {
            return $this->db->fetchRow();
        }
    }

    /**
     * Get fetch row
     * 
     * @param bool $printable = false
     * 
     * @return mixed
     */
    public function fetchRow(bool $printable = false)
    {
        $row = $this->db->fetchRow();

        if( $printable === false )
        {
            return $row;
        }
        else
        {
            return current((array) $row);
        }
    }

    /**
     * Get table row
     * 
     * @param mixed $printable = 0
     * 
     * @return mixed
     */
    public function row($printable = 0)
    {
        $result = $this->result('object', true);

        if( $printable < 0 )
        {
            $index = count($result) + $printable;

            return isset($result[$index]) ? (object) $result[$index] : false;
        }
        else
        {
            if( $printable === true )
            {
                $resultObject = new ArrayObject($result[0] ?? []);

                return $resultObject->getIterator()->current();
            }

            return isset($result[$printable]) ? (object) $result[$printable] : false;
        }
    }

    /**
     * Get table column value
     * 
     * @param string $column = NULL - added 5.6.5
     * 
     * @return string
     */
    public function value(string $column = NULL)
    {
        if( preg_match('/[a-z]\w+/i', $column ?? '') )
        {
            return $this->row()->$column ?? false;
        }

        return $this->row(true);
    }

    /**
     * Get affected rows
     * 
     * @return int
     */
    public function affectedRows() : int
    {
        return $this->db->affectedRows();
    }

    /**
     * Column Data
     * 
     * @param string $column = NULL
     * 
     * @return array
     */
    public function columnData(string $column = NULL)
    {
        return $this->db->columnData($column);
    }

    /**
     * Table Name
     * 
     * @return string
     */
    public function tableName() : string
    {
        return $this->tableName;
    }

    /**
     * Pagination
     * 
     * @param string $url      = NULL
     * @param array  $settings = []
     * @param bool   $output   = true
     * 
     * @return string
     */
    public function pagination(string $url = NULL, array $settings = [], bool $output = true)
    {
        $pagcon   = Config::get('ViewObjects', 'pagination');
        $getLimit = $this->db->getLimitValues($this->stringQuery());
        $start    = $getLimit['start'] ?? NULL;
        $limit    = $getLimit['limit'] ?? NULL;

        $settings['totalRows'] = $this->totalRows(true);
        $settings['limit']     = ! empty($limit) ? $limit : $pagcon['limit'];
        $settings['start']     = $start ?? $pagcon['start'];
        $settings['paging']    = $this->paging;

        if( $settings['paging'] === 'page' )
        {
            $settings['start'] = floor($settings['start'] / $settings['limit'] + 1);
        }

        if( ! empty($url) )
        {
            $settings['url'] = $url; // @codeCoverageIgnore
        }

        $return = $output === true
                ? Singleton::class('ZN\Pagination\Paginator')->create(NULL, $settings)
                : $settings;

        return $return;
    }

    /**
     * Is Exists
     * 
     * @param string $table
     * @param string $column
     * @param string $value
     * 
     * @param bool
     */
    public function isExists(string $table, string $column, string $value) : bool
    {
        return (bool) $this->where($column, $value)->get($table)->totalRows();
    }

    /**
     * Simple Result
     * 
     * @param string $table
     * @param string $column = NULL
     * @param string $value  = NULL
     * 
     * @return object
     */
    public function simpleResult(string $table, string $column = NULL, $value = NULL, $type = 'result')
    {
        if( $column !== NULL && $value !== NULL )
        {
            $this->where($column, $value);
        }

        return $this->get($table)->$type();
    }

    /**
     * Simple Result Array
     * 
     * @param string $table
     * @param string $column = NULL
     * @param string $value  = NULL
     * 
     * @return array
     */
    public function simpleResultArray(string $table, string $column = NULL, $value = NULL)
    {
        return $this->simpleResult($table, $column, $value, 'resultArray');
    }

    /**
     * Simple Row
     * 
     * @param string $table
     * @param string $column = NULL
     * @param string $value  = NULLL
     * 
     * @return object
     */
    public function simpleRow(string $table, string $column = NULL, $value = NULL)
    {
        return $this->simpleResult($table, $column, $value, 'row');
    }

    /**
     * Simple Total Rows
     * 
     * @param string $table
     * 
     * @return int
     */
    public function simpleTotalRows(string $table) : int
    {
        return $this->simpleResult($table, NULL, NULL, 'totalRows');
    }

    /**
     * Simple Total Columns
     * 
     * @param string $table 
     * 
     * @return int
     */
    public function simpleTotalColumns(string $table) : int
    {
        return $this->simpleResult($table, NULL, NULL, 'totalColumns');
    }

    /**
     * Simple Columns
     * 
     * @param string $table
     * 
     * @return array
     */
    public function simpleColumns(string $table) : array
    {
        return $this->simpleResult($table, NULL, NULL, 'columns');
    }

    /**
     * Simple Column Data
     * 
     * @param string $table
     * @param string $column
     * 
     * @return stdClass
     */
    public function simpleColumnData(string $table, string $column = NULL) : \stdClass
    {
        return $this->get($table)->columnData($column);
    }

    /**
     * Simple Update
     * 
     * @param string $table
     * @param string $data
     * @param string $column
     * @param string $value
     * 
     * @return bool
     */
    public function simpleUpdate(string $table, array $data, string $column, string $value)
    {
        return $this->where($column, $value)->update($table, $data);
    }

    /**
     * Simple Delete
     * 
     * @param string $table
     * @param string $column
     * @param string $value
     * 
     * @return bool
     */
    public function simpleDelete(string $table, string $column, string $value)
    {
        return $this->where($column, $value)->delete($table);
    }

    /**
     * Switch Case
     * 
     * @param string $switch
     * @param array  $conditions = []
     * @param bool   $return     = false
     * 
     * @return mixed
     */
    public function switchCase(string $switch, array $conditions = [], bool $return = false)
    {
        $case  = ' CASE '.$switch;

        $alias = '';

        if( isset($conditions['as']) )
        {
            $alias = ' as ' . $conditions['as'] . ' ';

            unset($conditions['as']);
        }

        if( is_array($conditions) ) foreach( $conditions as $key => $val )
        {
            if( strtolower($key) === 'default' || strtolower($key) === 'else' )
            {
                $key = ' ELSE ';
            }
            else
            {
                $key = ' WHEN ' . $key . ' THEN ';
            }

            $case .= $key . $val;
        }

        $case .= ' END ' . $alias;

        if( $return === true )
        {
            return $case;
        }
        else
        {
            $this->selectFunctions[] = $case;

            return $this;
        }
    }

    /**
     * Vartype
     * 
     * @param mixed  $type
     * @param string $len    = NULL
     * @param bool   $output = true
     * 
     * @return string
     */
    public function vartype(string $type, int $len = NULL, bool $output = true) : string
    {
        return $this->db->variableTypes($type, $len, $output);
    }

    /**
     * Property
     * 
     * @param mixed  $type
     * @param string $col    = NULL
     * @param bool   $output = true
     * 
     * @return string
     */
    public function property($type, string $col = NULL, bool $output = true) : string
    {
        if( is_array($type) )
        {
            $state = '';

            foreach( $type as $key => $val )
            {
                if( ! is_numeric($key) )
                {
                    $state .= $this->db->statements($key, $val);
                }
                else
                {
                    $state .= $this->db->statements($val);
                }
            }

            return $state;
        }
        else
        {
            return $this->db->statements($type, $col, $output);
        }
    }

    /**
     * Defines SQL DEFAULT
     * 
     * @param string $default = NULL
     * @param string $bool    = false
     * 
     * @return string
     */
    public function defaultValue(string $default = NULL, bool $type = false) : string
    {
        if( ! is_numeric($default) )
        {
            $default = Base::presuffix($default, '"');
        }

        return $this->db->statements('default', $default, $type);
    }

    /**
     * Defines SQL LIKE Operators
     * 
     * @param string $value
     * @param string $type = 'starting' - Options[starting|ending|inside]
     * 
     * @return string
     */
    public function like(string $value, string $type = 'starting') : string
    {
        $operator = $this->db->operator(__FUNCTION__);

        if( $type === "inside" )
        {
            $value = $operator.$value.$operator;
        }

        // İle Başlayan
        if( $type === "starting" )
        {
            $value = $value.$operator;
        }

        // İle Biten
        if( $type === "ending" )
        {
            $value = $operator.$value;
        }

        return $value;
    }

    /**
     * Defines SQL BETWEEN
     * 
     * @param string $value1
     * @param string $value2
     * 
     * @return string
     */
    public function between(string $value1, string $value2) : string
    {
        return $this->escapeStringAddNail($value1, true).' AND '.$this->escapeStringAddNail($value2, true);
    }

    /**
     * Defines SQL NOT IN
     * 
     * @param string ...$value 
     * 
     * @return string
     */
    public function notIn(...$value) : string
    {
        return $this->buildInClause('in', ...$value);
    }

    /**
     * Defines SQL IN
     * 
     * @param string ...$value 
     * 
     * @return string
     */
    public function in(...$value) : string
    {
        return $this->buildInClause(__FUNCTION__, ...$value);
    }

    /**
     * protected IN Table
     * 
     * @param string ...$value 
     * 
     * @return string
     */
    public function inTable(...$value) : string
    {
        return $this->buildInClause(__FUNCTION__, ...$value);
    }

    /**
     * protected IN Query
     * 
     * @param string ...$value 
     * 
     * @return string
     */
    public function inQuery(...$value) : string
    {
        return $this->buildInClause(__FUNCTION__, ...$value);
    }

    /**
     * protected is hash id column
     */
    protected function isHashIdColumn(&$datas)
    {
        $this->hashId = NULL;

        if( $this->hashIdColumn )
        {
            $this->hashId = $datas[$this->hashIdColumn] = $this->createHashId($datas);

            $this->hashIdColumn = NULL;
        }
    }

    /**
     * protected build insert query
     */
    protected function buildInsertQuery($table, $data, $values)
    {
        return 'INSERT '.
                $this->lowPriority.
                $this->delayed.
                $this->highPriority.
                $this->ignore.
                ' INTO '.
                $this->addPrefixForTableAndColumn($table).
                $this->partition.
                $this->buildInsertValuesClause($data, $values) . 
                $this->db->getInsertExtrasByDriver();
    }

    /**
     * protected duplicate check process
     */
    protected function duplicateCheckProcess($duplicateCheckWhere, $table, $datas, &$return = NULL)
    {
        $this->isUpdate = false;
        
        if( ! empty($duplicateCheckWhere) && $this->where($duplicateCheckWhere)->count('*')->get($table)->value() )
        {
            $this->duplicateCheck = NULL; $return = false;

            if( $this->duplicateCheckUpdate === true )
            {
                $this->duplicateCheckUpdate = NULL;

                $this->isUpdate = true;

                $return = $this->where($duplicateCheckWhere)->update($table, $datas);
            }
        }
    }

    /**
     * protected build data values query
     */
    protected function buildDataValuesQueryForInsert($datas, &$data, &$values, &$duplicateCheckWhere)
    {
        $data = ''; $values = ''; $duplicateCheckWhere = [];
    
        foreach( $datas as $key => $value )
        {
            if( $this->isExpressionExists($key) )
            {
                $key = $this->clearExpression($key); $isExp = true;
            }

            $this->isNonscalarValueEncodeJson($value);

            $data .= Base::suffix($key, ',');
            
            if( ! empty($this->duplicateCheck) && ($this->duplicateCheck[0] === '*' || in_array($key, $this->duplicateCheck)) )
            {
                $duplicateCheckWhere[] = [$key . ' = ', $value, 'and'];
            }

            $value = $this->nailEncode($value);

            if( isset($isExp) )
            {
                $values .= Base::suffix($value, ','); unset($isExp);
            }
            else
            {
                $values .= Base::suffix(Base::presuffix($value, "'"), ',');
            }
        }
    }

    /**
     * protected multi insert
     */
    protected function multiInsert($table, $datas)
    {
        $data  = $datas[0]; unset($datas[0]);

        $query = $this->string()->insert($table, $data);
        
        foreach( $datas as $values )
        {
            $value = '';

            foreach( $values as $val )
            {
                $value .= Base::suffix(Base::presuffix($this->nailEncode($val), "'"), ',');
            }

            $query .= ', (' . rtrim($value, ',') . ')';
        } 

        return Base::suffix($query, ';');
    }

    /**
     * protected build data values query for update
     */
    protected function buildDataValuesQueryForUpdate($datas, &$data)
    {
        $data = '';
        
        foreach( $datas as $key => $value )
        {
            $this->isNonscalarValueEncodeJson($value);

            $value = $this->nailEncode($value);

            if( $this->isExpressionExists($key) )
            {
                $key = $this->clearExpression($key); // @codeCoverageIgnore
            }
            else
            {
                $value = Base::presuffix($value, "'");
            }

            $data .= $key . '=' . Base::suffix($value, ',');
        }
    }

    /**
     * protected build update query
     */
    protected function buildUpdateQuery($table, $data, &$where = NULL)
    {
        $where = $this->buildWhereClause();

        return 'UPDATE '.
                $this->lowPriority.
                $this->ignore.
                $this->addPrefixForTableAndColumn($table).
                $this->join.
                ' SET ' . substr($data, 0, -1) .
                $where.
                $this->buildOrderByClause().
                $this->limit . 
                $this->db->getInsertExtrasByDriver();
    }

    /**
     * protected build delete query
     */
    protected function buildDeleteQuery($table)
    {
        return 'DELETE '.
                $this->lowPriority.
                $this->quick.
                $this->ignore.
                $this->deleteJoinTables($table).
                ' FROM '.
                $this->addPrefixForTableAndColumn($table).
                $this->join.
                $this->partition.
                $this->buildWhereClause().
                $this->buildOrderByClause().
                $this->limit;
    }

    /**
     * protected run transaction queries
     */
    protected function runTransactionQueries()
    {
        if( $this->transactionQueries )
        {
            foreach( $this->transactionQueries as $query )
            {
                $this->transQuery($query);
            }
        }
    }

    /**
     * protected special defined where
     */
    protected function specialDefinedWhere($column, $value = NULL, string $logical = NULL, $type = 'whereJson')
    {
        $this->where('exp:' . $this->db->$type($column, $this->escapeStringAddNail((string) $value)), '', $logical, 'where');
    }

    /**
     * protected IN
     * 
     * @param string    $type = 'in'
     * @param string ...$value 
     * 
     * @return string
     */
    protected function buildInClause($type = 'in', ...$value)
    {
        $query = '(';
        $type  = strtolower($type);

        foreach( $value as $val )
        {
            if( $type === 'in' )
            {
                $query .= $this->escapeStringAddNail($val, true);
            }
            elseif( $type === 'intable' )
            {
                $query .= $this->getString($val);
            }
            else
            {
                $query .= $val;
            }

            $query .= ',';
        }

        return rtrim($query, ',') . ')';
    }

    /**
     * protected is nonscalar value encode json
     */
    protected function isNonscalarValueEncodeJson(&$value)
    {
        if( ! is_scalar($value) )
        {
            $value = Json::encode($value);
        }
    }

    /**
     * protected Select
     * 
     * @return string
     */
    protected function buildSelectClause()
    {
        if( ! empty($this->selectFunctions) )
        {
            $selectFunctions = rtrim(implode(',', $this->selectFunctions), ',');

            if( empty($this->select) )
            {
                $this->select = $selectFunctions;
            }
            else
            {
                $this->select .= ',' . $selectFunctions; // @codeCoverageIgnore
            }
        }

        if( empty($this->select) )
        {
            $this->select = ' * ';
        }

        return $this->select;
    }

    /**
     * protected Values
     * 
     * @param string $data
     * @param string $values
     * 
     * @return string
     */
    protected function buildInsertValuesClause($data, $values)
    {
        return ' ('.rtrim($data, ',').') VALUES ('.rtrim($values, ',').')';
    }

    /**
     * protected Result Cache
     * 
     * @param string $type
     */
    protected function getCacheResult($type, &$results = [])
    {
        if( ! empty($this->caching) )
        {
            $driver = $this->caching['driver'] ?? 'file';

            $cache = Singleton::class('ZN\Cache\Processor');

            if( $cacheResult = $cache->driver($driver)->select($this->getEncryptedCacheQuery()) )
            {
                $results = $cacheResult; // @codeCoverageIgnore
            }
            else
            {
                $cache->driver($driver)->insert($this->getEncryptedCacheQuery(), $results = $this->db->result($type), $this->caching['time'] ?? 0);
            }
        }
    }

    /**
     * protected Ignore Data
     * 
     * @param string & $table
     * @param string & $data
     */
    protected function ignoreData(&$table, &$data)
    {
        $table = $table ?? '';
        
        $methods = ['ignore', 'post', 'get', 'request'];        

        if( stristr($table, ':') )
        {
            $tableEx = explode(':', $table);
            $method  = $tableEx[0];
            $table   = $tableEx[1];

            if( in_array($method, $methods) )
            {
                if( $method !== 'ignore' )
                {
                    $data = Method::$method();
                }

                $columns = array_flip($this->setQueryByDriver('SELECT * FROM ' . $table)->columns());
                $data    = array_intersect_key($data, $columns);

                $this->unsetData($data);
            }
        }
    }

    /**
     * protected unset data
     */
    protected function unsetData(&$data)
    {
        # The ID column is removed by default.
        if( $find = preg_grep('/(^(id)$)/i', array_keys($data)) )
        {
            $id = current($find); unset($data[$id]);
        }

        # The columns you specified are removed.
        if( ! empty($this->unset) )
        {
            foreach( $this->unset as $column )
            {
                unset($data[$column]);
            }

            $this->unset = [];
        }
    }

    /**
     * protected CSV
     * 
     * @param string & $data
     */
    protected function convertCSVData(&$data)
    {
        $csv       = Converter::CSVToArray($data);
        $csvColumn = $csv[0];

        array_shift($csv);

        $csvDatas  = $csv;
        $data      = array_map(function($d) use($csvColumn)
        {
            return array_combine($csvColumn, $d);
        }, $csvDatas);
    }

    /**
     * protected Delete Join Tables
     * 
     * @param string $table
     * 
     * @return string
     */
    protected function deleteJoinTables($table)
    {
        if( $this->join )
        {
            $joinType = strtolower($this->joinType ?? '');

            if( $joinType === 'inner' )
            {
                $joinTables = $this->addPrefixForTableAndColumn($table) . ', ' . $this->joinTable; // @codeCoverageIgnore
            }
            elseif( $joinType === 'right' )
            {
                $joinTables = $this->joinTable; // @codeCoverageIgnore
            }
            else
            {
                $joinTables = $this->addPrefixForTableAndColumn($table);
            }

            $this->joinType  = NULL;
            $this->joinTable = NULL;

            return Base::presuffix($joinTables, ' ');
        }

        return NULL;
    }

    /**
     * protected Where Key Control
     * 
     * @param string $column
     * @param string $value
     * 
     * @return string
     */
    protected function whereKeyControl($column, $value)
    {
        $keys   = ['between', 'in'];
        $column = trim($column);

        if( in_array(strtolower(Datatype::divide($column, ' ', -1)), $keys) || $this->isExpressionExists($column) )
        {
            return $value;
        }

        return $this->escapeStringAddNail($value);
    }

    /**
     * protected Equal Control
     * 
     * @param string $column
     * 
     * @return string
     */
    protected function setEqualClause($column)
    {
        $column = $column ?? ''; $control = trim($column);

        if( strstr($column, '.') )
        {
            $control = str_replace('.', '', $control);
        }

        if( preg_match('/^\w+$/', $control) )
        {
            $column .= ' = ';
        }

        return $column;
    }

    /**
     * protected Where Having
     * 
     * @param mixed  $column
     * @param string $value
     * @param string $logical
     * 
     * @return string
     */
    protected function whereHaving($column, $value, $logical)
    {
        if( $value !== '' )
        {
            $value = $this->whereKeyControl($column, $value);
        }

        $this->convertVartype($column, $value);

        $column = $this->setEqualClause($column);

        return ' '.$this->tablePrefixColumnControl($column).' '.$value.' '.$logical.' ';
    }

    /**
     * protected Where
     * 
     * @param mixed  $column
     * @param string $value
     * @param string $logical
     * @param string $type = 'where'
     * 
     * @return DB
     */
    protected function buildWhereHavingClause($column, $value, $logical, $type = 'where')
    {   
        if( is_array($column) )
        {
            $columns = func_get_args();

            if( isset($columns[0][0]) && is_array($columns[0][0]) )
            {
                $columns = $columns[0];
            }

            foreach( $columns as $col )
            {
                if( is_array($col) )
                {
                    $c = $col[0] ?? '';
                    $v = $col[1] ?? '';
                    $l = $col[2] ?? 'and';

                    $this->$type .= $this->whereHaving($c, $v, $l);
                }
            }
        }
        else
        {
            $this->$type .= $this->whereHaving($column, $value, $logical ?: 'and');
        }

        return $this;
    }

    /**
     * protected Where Having Group
     * 
     * @param array $condition = []
     * 
     * @return string
     */
    protected function whereHavingGroup($conditions = [])
    {
        $con = [];

        // @codeCoverageIgnoreStart
        if( isset($conditions[0][0]) && is_array($conditions[0][0]) )
        {
            $con        = Arrays\GetElement::last($conditions);
            $conditions = $conditions[0];
        }
        // @codeCoverageIgnoreEnd

        $getLast = Arrays\GetElement::last($conditions);

        if( is_string($con) )
        {
            $conjunction = $con; // @codeCoverageIgnore
        }
        else
        {
            if( is_string($getLast) )
            {
                $conjunction = $getLast;
                array_pop($conditions);
            }
            else
            {
                $conjunction = 'and';
            }
        }

        $whereGroup = '';

        if( is_array($conditions) ) foreach( $conditions as $column )
        {
            $col     = $column[0] ?? '';
            $value   = $column[1] ?? '';
            $logical = $column[2] ?? 'and';

            $whereGroup .= $this->whereHaving($col, $value, $logical);
        }

        return ' ( '.$this->whereHavingConjuctionClean($whereGroup).' ) '.$conjunction.' ';
    }

    /**
     * protected Where Having Conjuction Control
     * 
     * @param string $type
     * 
     * @return string
     */
    protected function whereHavingConjuctionControl($type)
    {
        if( ! empty($this->$type) )
        {
            $this->$type = $this->whereHavingConjuctionClean($this->$type) ?: $this->$type;

            $return = ' '.strtoupper($type).' '.$this->$type;

            $this->$type = NULL;

            return $return;
        }
    }

    /**
     * protected Where Having Conjuction Clean
     * 
     * @param string $str
     * 
     * @return string
     * 
     * @codeCoverageIgnore
     */
    protected function whereHavingConjuctionClean($str)
    {
        if( ! empty($str) )
        {
            $str = strtolower($orgstr = trim($str));

            switch( substr($str, -3) )
            {
                case 'and' :
                case 'xor' :
                case 'not' :
                return substr($orgstr, 0, -3);
            }

            switch( substr($str, -2) )
            {
                case 'or' :
                case '||' :
                case '&&' :
                return substr($orgstr, 0, -2);
            }

            switch( substr($str, -1) )
            {
                case '!' :
                return substr($orgstr, 0, -1);
            }
        }

        return $str;
    }

    /**
     * protected Where
     * 
     * @return string
     */
    protected function buildWhereClause()
    {
        return $this->whereHavingConjuctionControl('where');
    }

    /**
     * protected Having
     * 
     * @return string
     */
    protected function buildHavingClause()
    {
        return $this->whereHavingConjuctionControl('having');
    }

    /**
     * protected Join
     * 
     * @param string $tableAndColumn = ''
     * @param string $otherColumn    = ''
     * @param string $operator       = '='
     * @param string $type           = 'INNER'
     * 
     * @param object
     */
    protected function buildJoinClause($tableAndColumn = '', $otherColumn = '', $operator = '=', $type = 'INNER')
    {
        $condition = $this->tablePrefixColumnControl($tableAndColumn, $table).' '.
                     $operator.' '.
                     $this->tablePrefixColumnControl($otherColumn).' ';
        
        $this->join($table, $condition, $type);
    }

    /**
     * protected Table Prefix Column Control
     * 
     * @param string $column
     * @param string & $table = NULL
     * 
     * @return string
     */
    protected function tablePrefixColumnControl($column, &$table = NULL)
    {
        $column = explode('.', $column);

        # For table
        switch( $count = count($column) )
        {
            case 3 : $table = $column[0] . '.' . $column[1]; break;
            case 1 : 
            case 2 :
            default: $table = $column[0]; break;
        }

        # For column
        switch( $count )
        {
            case 2 : return $this->prefix . $column[0] . '.' . $column[1];
            case 3 : return $column[0] . '.' . $this->prefix . $column[1] . '.' . $column[2];
            case 1 : 

            default: return $column[0];
        }
    }

    /**
     * protected Group By
     * 
     * @return mixed
     */
    protected function buildGroupByClause()
    {
        if( ! empty($this->groupBy) )
        {
            return ' GROUP BY '.rtrim($this->groupBy, ', ');
        }

        return false;
    }

    /**
     * protected Order By
     * 
     * @return mixed
     */
    protected function buildOrderByClause()
    {
        if( ! empty($this->orderBy) )
        {
            return ' ORDER BY '.rtrim($this->orderBy, ', ');
        }

        return false;
    }

    /**
     * protected Increment & Decrement
     * 
     * @param string $table
     * @param array  $columns
     * @param int    $incdec
     * @param string $type
     * 
     * @return bool
     */
    protected function setIncrementDecrement($table, $columns, $incdec, $type)
    {
        $newColumns = [];

        $table   = $this->addPrefixForTableAndColumn($table);
        $columns = $this->addPrefixForTableAndColumn($columns, 'column');
        $incdec  = $type === 'increment' ? abs($incdec) : -abs($incdec);

        if( is_array($columns) ) foreach( $columns as $v )
        {
            $newColumns[$v] = "$v + $incdec";
        }
        else
        {
            $newColumns = [$columns => "$columns + $incdec"];
        }

        $data = '';

        foreach( $newColumns as $key => $value )
        {
            $data .= $key.'='.$value.',';
        }

        $set = ' SET '.substr($data, 0, -1);

        $updateQuery = 'UPDATE '.$this->prefix.$table.$set.$this->buildWhereClause();

        $this->where = NULL;

        if( $this->string === true )
        {
            return $updateQuery; // @codeCoverageIgnore
        }

        if( $this->transaction === true )
        {
            $this->transactionQueries[] = $updateQuery; // @codeCoverageIgnore

            return $this; // @codeCoverageIgnore
        }

        return $this->db->query($updateQuery);
    }

    /**
     * Query
     * 
     * @param string $query
     * @param array  $secure = []
     * @param mixed  $data   = NULL
     * 
     * @return DB 
     */
    public function setQueryByDriver(string $query, array $secure = [], $data = NULL)
    {
        $this->stringQuery = $query;
        $this->caching     = $data['caching']    ?? [];
        $this->tableName   = $data['tableName']  ?? '';
        $this->jsonDecode  = $data['jsonDecode'] ?? [];
        $this->paging      = $data['paging']     ?? 'page';

        if( empty($this->caching) || ! Singleton::class('ZN\Cache\Processor')->select($this->getEncryptedCacheQuery()) )
        {
            $this->secure = $this->secure ?: $secure;

            $this->db->query($this->querySecurity($query), $secure);

            if( ! empty($this->transStart) )
            {
                $transError = $this->db->error();

                if( ! empty($transError) )
                {
                    $this->transError = $transError;
                }
            }
        }

        return $this;
    }

    /**
     * protected Cache Query
     * 
     * @return string
     */
    protected function getEncryptedCacheQuery()
    {
        return md5(Json::encode($this->config) . $this->stringQuery());
    }
    
    /**
     * protected reset transaction query variables
     */
    protected function resetTransactionQueryVariables()
    {
        $this->transStart         = NULL;
        $this->transError         = NULL;
        $this->transaction        = false;
        $this->transactionQueries = [];
    }

    /**
     * protected Select Reset Query
     */
    protected function resetSelectQueryVariables()
    {
        $this->distinct        = NULL;
        $this->highPriority    = NULL;
        $this->straightJoin    = NULL;
        $this->smallResult     = NULL;
        $this->bigResult       = NULL;
        $this->bufferResult    = NULL;
        $this->cache           = NULL;
        $this->calcFoundRows   = NULL;
        $this->select          = NULL;
        $this->from            = NULL;
        $this->table           = NULL;
        $this->where           = NULL;
        $this->groupBy         = NULL;
        $this->having          = NULL;
        $this->orderBy         = NULL;
        $this->limit           = NULL;
        $this->join            = NULL;
        $this->selectFunctions = NULL;
        $this->table           = NULL;
        $this->partition       = NULL;
        $this->procedure       = NULL;
        $this->outFile         = NULL;
        $this->characterSet    = NULL;
        $this->into            = NULL;
        $this->forUpdate       = NULL;
        $this->returnQueryType  = NULL;
    }

    /**
     * protected Reset Insert Query
     */
    protected function resetInsertQueryVariables()
    {
        $this->column          = NULL;
        $this->table           = NULL;
        $this->highPriority    = NULL;
        $this->lowPriority     = NULL;
        $this->partition       = NULL;
        $this->ignore          = NULL;
        $this->delayed         = NULL;
        $this->duplicateCheck  = NULL;
        $this->duplicateCheckUpdate = NULL;
    }

    /**
     * protected Reset Update Query
     */
    protected function resetUpdateQueryVariables()
    {
        $this->where           = NULL;
        $this->lowPriority     = NULL;
        $this->ignore          = NULL;
        $this->orderBy         = NULL;
        $this->limit           = NULL;
        $this->table           = NULL;
        $this->join            = NULL;
        $this->column          = NULL;
    }

    /**
     * protected Reset Delete Query
     */
    protected function resetDeleteQueryVariables()
    {
        $this->where           = NULL;
        $this->lowPriority     = NULL;
        $this->quick           = NULL;
        $this->ignore          = NULL;
        $this->join            = NULL;
        $this->partition       = NULL;
        $this->orderBy         = NULL;
        $this->limit           = NULL;
        $this->table           = NULL;
    }
}
