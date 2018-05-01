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

use ZN\Datatype;

trait CallableTalkingQueries
{
    /**
     * Magic call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $method = strtolower($originMethodName = $method);
        $split  = Datatype::splitUpperCase($originMethodName);

        # Is Function Elements
        if( in_array($method, $this->functionElements) )
        {
            $functionMethod = $method;
        }
        else
        {
            $functionMethod = $this->functionElements[$method] ?? NULL;
        }

        # Is Vartype Elements
        if( in_array($method, $this->vartypeElements) )
        {
            $vartypeMethod = $method;
        }
        else
        {
            $vartypeMethod  = $this->vartypeElements[$method]  ?? NULL;
        }

        # Math Functions
        if( $functionMethod !== NULL )
        {
            return $this->callMathMethod($functionMethod, $parameters);
        }
        # Variable Types
        elseif( $vartypeMethod !== NULL )
        {
            return $this->db->variableTypes($vartypeMethod, ...$parameters);
        }
        # Statements
        elseif( in_array($method, $this->statementElements) )
        {
            return $this->db->statements($method, ...$parameters);
        }
        # Join
        elseif( ($split[1] ?? NULL) === 'Join')
        {
            return $this->callJoinTalkingQuery($split, $parameters);
        }
        # Order By - Group By
        elseif( $split[0] === 'order' || $split[0] === 'group')
        {
            return $this->callOrderGroupByTalkingQuery($split);
        }
        # Where - Having
        elseif( $split[0] === 'where' || $split[0] === 'having' )
        {
            return $this->callWhereHavingTalkingQuery($split, $parameters);
        }
        # Insert - Update - Delete
        elseif( in_array($split[1] ?? NULL, ['Delete', 'Update', 'Insert']) )
        {
            return $this->callCrudTalkingQuery($split, $parameters);
        }
        else
        {
            return $this->callResultMethodsTalkingQuery($method, $split, $parameters);
        }
    }

    /**
     * Protected call join talkin query
     */
    protected function callJoinTalkingQuery($split, $parameters)
    {
        $type    = $split[0] ?? 'left';
        $table1  = $split[2] ?? NULL;
        $column1 = strtolower($table1 . '.' . $split[3]);
        $table2  = $split[4] ?? NULL;
        $column2 = strtolower($table2 . '.' . $split[5]);
        $met     = $type . $split[1];

        return $this->$met($column1, $column2, $parameters[0] ?? '=');
    }

    /**
     * Protected call order group by talking query
     */
    protected function callOrderGroupByTalkingQuery($split)
    {
        $column = strtolower($split[2] ?? NULL);
        $type   = $split[0] === 'order' ? $split[3] ?? 'asc' : NULL;
        $met    = $split[0] . 'By';

        return $this->$met($column, $type);
    }

    /**
     * Protected call crud talking query
     */
    protected function callCrudTalkingQuery($split, $parameters)
    {
        $table  = $split[0];
        $method = $split[1];

        if( is_string($parameters[0]) )
        {
            $prefix = $parameters[0] . ':';
            $data   = [];
        }
        else
        {
            $prefix = NULL;
            $data   = $parameters[0];
        }

        # [5.6.5] In case of using 3rd section, it is accepted as a condition.
        if( isset($split[2]) )
        {
            # For delete: tableDeleteColumn($value)
            if( $method === 'Delete' )
            {
                $this->where($split[2], $data);
            }
            # For update: tableUpdateColumn($data, $value)
            elseif( $method === 'Update' && isset($parameters[1]) )
            {
                $this->where($split[2], $parameters[1]);
            }
        }

        return $this->$method($prefix . $table, $data);
    }

    /**
     * Protected call where having talking query
     */
    protected function callWhereHavingTalkingQuery($split, $parameters)
    {
        $met       = $split[0];
        $column    = strtolower($split[1]);
        $condition = $split[2] ?? NULL;
        $operator  = isset($parameters[1]) ? ' ' . $parameters[1] : NULL;

        return $this->$met($column . $operator, $parameters[0], $condition);
    }

    /**
     * updated[5.7.0.1]
     * Protected call result methods talking query
     */
    protected function callResultMethodsTalkingQuery($method, $split, $parameters)
    {
        $func = $split[1] ?? NULL;

        # Row & Result
        if( $func === 'Row' || $func === 'Result' )
        {
            $method = $split[0];
            $result = strtolower($func);
        }

        $whereClause = $parameters[0] ?? ($result === 'row' ? 0 : 'object');

        # Value
        if( $select = ($split[2] ?? NULL) )
        {
            if( isset($parameters[0]) )
            {
                $this->where(strtolower($split[2]), $parameters[0]);    

                $whereClause = 0;
            }
            else
            {
                $result = 'value';

                $this->select($select);

                $whereClause = true;
            }    
        }

        $return = $this->get($method);

        # Return ->get()
        if( ! isset($result) )
        {
            return $return;
        }
        
        # Return ->row(0) || result('object')
        return $return->$result($whereClause);
    }

    /**
     * Protected call math method
     */
    protected function callMathMethod($functionMethod, $parameters)
    {
        $math = $this->_math($functionMethod, $parameters);

        if( $math->return === true )
        {
            return $math->args;
        }
        else
        {
            $this->selectFunctions[] = $math->args;

            return $this;
        }
    }
}
