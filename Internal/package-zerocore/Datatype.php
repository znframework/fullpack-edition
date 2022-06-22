<?php namespace ZN;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Datatype
{   
    /**
     * Case Array
     * 
     * @param array  $array
     * @param string $type   - options[lower|upper|title]
     * @param string $keyval - options[all|key|value]
     * 
     * @return array
     */
    public static function caseArray(array $array, string $type = 'lower', string $keyval = 'all') : array
    {
        $callback = function($data) use($type)
        {
            return mb_convert_case($data, Helper::toConstant($type, 'MB_CASE_'));
        };
   
        $arrayVals = array_values($array); $arrayKeys = array_keys($array);
      
        switch( $keyval )
        {
            case 'key'  : $arrayKeys = array_map($callback, $arrayKeys); break;
            case 'value': $arrayVals = array_map($callback, $arrayVals); break;
            case 'all'  :
            default     : $arrayKeys = array_map($callback, $arrayKeys);
                          $arrayVals = array_map($callback, $arrayVals);
        }
   
        return array_combine($arrayKeys, $arrayVals);
    }

    /**
     * Multiple Key
     * 
     * @param array  $array
     * @param string $keySplit = '|'
     * 
     * @return array
     */
    public static function multikey(array $array, string $keySplit = '|') : array
    {
        $newArray = [];

        foreach( $array as $k => $v )
        {
            $keys = explode($keySplit, $k);

            foreach( $keys as $val )
            {
                $newArray[$val] = $v;
            }
        }

        return $newArray;
    }

    /**
     * Divide
     * 
     * @param string $str       = NULL
     * @param string $separator = '|'
     * @param string $index     = '0'
     * @param string $count     = '1'
     */
    public static function divide(string $str = NULL, string $separator = '|', string $index = '0', string $count = '1')
    {
        $arrayEx = explode($separator, $str ?? '');

        if( $index === 'all' )
        {
            return $arrayEx;
        }

        switch( true )
        {
            case $index < 0        : $ind = (count($arrayEx) + ($index)); break;
            case $index === 'last' : $ind = (count($arrayEx) - 1);        break;
            case $index === 'first': $ind = 0;                            break;
            default                : $ind = $index;
        }

        if( $count === '1' )
        {
            return $arrayEx[$ind] ?? false;
        }
        else
        {
            $return = '';

            if( $count === 'all' )
            {
                $count = count($arrayEx) - $ind;
            }
            elseif( $count < 0 )
            {
                $count = count($arrayEx) + $count + 1;
            }

            for( $i = 0; $i < $count; $i++ )
            {
                if( ! isset($arrayEx[$ind + $i]) )
                {
                    break;
                }
                
                $return .= $arrayEx[$ind + $i] . $separator;
            }

            return Base::removeSuffix($return, $separator);
        }
    }

    /**
     * Split Upper Case
     * 
     * @param string $string
     * 
     * @return array
     */
    public static function splitUpperCase(string $string) : array
    {
        return preg_split('/(?=[A-Z])/', $string, -1, PREG_SPLIT_NO_EMPTY);
    }
}