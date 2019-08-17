<?php namespace ZN\DataTypes\Arrays;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Search
{
    /**
     * Finds the expression between two expressions, including parameters. By value to key.
     * 
     * @param array $array
     * @param mixed $start
     * @param mixed $end
     * 
     * @return array|bool
     */
    public static function betweenBothWithValueToKey(Array $array, $start, $end)
    {
        $startPosition = array_search($start, $array);

        if( isset($array[$start]) && $startPosition !== false )
        {
            return self::betweenBothForeachFunction($array, $startPosition, $end);
        }

        return false;
    }

    /**
     * Finds the expression between two expressions, including parameters. By key to value.
     * 
     * @param array $array
     * @param mixed $start
     * @param mixed $end
     * 
     * @return array|bool
     */
    public static function betweenBothWithKeyToValue(Array $array, $start, $end)
    {
        $endPosition = array_search($end, $array);

        if( isset($array[$start]) && $endPosition !== false )
        {
            return self::betweenBothForeachFunction($array, $start, $endPosition);
        }

        return false;
    }

    /**
     * Finds the expression between two statements.
     * 
     * @param array $array
     * @param mixed $start
     * @param mixed $end
     * 
     * @return array|bool
     */
    public static function betweenWithValueToKey(Array $array, $start, $end)
    {
        $startPosition = array_search($start, $array);

        if( isset($array[$start]) && $startPosition !== false )
        {
            return self::betweenForeachFunction($array, $startPosition, $end);
        }

        return false;
    }

    /**
     * Finds the expression between two statements.
     * 
     * @param array $array
     * @param mixed $start
     * @param mixed $end
     * 
     * @return array|bool
     */
    public static function betweenWithKeyToValue(Array $array, $start, $end)
    {
        $endPosition = array_search($end, $array);

        if( isset($array[$start]) && $endPosition !== false )
        {
            return self::betweenForeachFunction($array, $start, $endPosition);
        }

        return false;
    }

    /**
     * Finds the expression between two statements.
     * 
     * @param array $array
     * @param mixed $start
     * @param mixed $end
     * 
     * @return array|bool
     */
    public static function betweenWithKey(Array $array, $start, $end)
    {
        if( isset($array[$start]) )
        {
            return self::betweenForeachFunction($array, $start, $end);
        }


        return false;
    }

    /**
     * Finds the expression between two expressions, including parameters.
     * 
     * @param array $array
     * @param mixed $start
     * @param mixed $end
     * 
     * @return array|bool
     */
    public static function betweenBothWithKey(Array $array, $start, $end)
    {
        if( isset($array[$start]) )
        {
            return self::betweenBothForeachFunction($array, $start, $end);
        }

        return false;
    }

    /**
     * Finds the expression between two statements.
     * 
     * @param array $array
     * @param mixed $start
     * @param mixed $end
     * 
     * @return array|bool
     */
    public static function between(Array $array, $start, $end)
    {
        if( self::betweenFunction($array, $start, $end, $startPosition, $endPosition) )
        {
            return self::betweenForeachFunction($array, $startPosition, $endPosition);
        }

        return false;
    }

   /**
     * Finds the expression between two expressions, including parameters.
     * 
     * @param array $array
     * @param mixed $start
     * @param mixed $end
     *
     * @return array|bool
     */
    public static function betweenBoth(Array $array, $start, $end) 
    {
        if( self::betweenFunction($array, $start, $end, $startPosition, $endPosition) )
        {
            return self::betweenBothForeachFunction($array, $startPosition, $endPosition);
        }

        return false;
    }

    /**
     * Protected between foreach function.
     */
    protected static function betweenForeachFunction($array, $startPosition, $endPosition)
    {
        $foundElements = []; $searchStatus = false;

        foreach( $array as $key => $val )
        {
            if( $key === $endPosition )
            {
                break;
            }

            if( $searchStatus === true )
            {
                $foundElements[$key] = $val;
            }

            if( $key === $startPosition )
            {
                $searchStatus = true;
            }                     
        }

        return $foundElements;
    }

    /**
     * Protected between both foreach function.
     */
    protected static function betweenBothForeachFunction($array, $startPosition, $endPosition)
    {
        $foundElements = []; $searchStatus = false;

        foreach( $array as $key => $val )
        {    
            if( $key === $startPosition )
            {
                $searchStatus = true;
            }       
            
            if( $searchStatus === true )
            {
                $foundElements[$key] = $val;
            }
            
            if( $key === $endPosition )
            {
                break;
            }
        }

        return $foundElements;
    }

    /**
     * Protected between function.
     */
    protected static function betweenFunction($array, $start, $end, &$startPosition, &$endPosition)
    {  
        $startPosition = array_search($start, $array);
        $endPosition   = array_search($end  , $array);

        return $startPosition !== false && $endPosition !== false;
    }
}
