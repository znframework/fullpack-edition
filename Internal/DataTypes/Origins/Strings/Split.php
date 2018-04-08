<?php namespace ZN\DataTypes\Strings;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Split
{
    //--------------------------------------------------------------------------------------------------------
    // Split Upper Case -> 5.2.0
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $table
    //
    //--------------------------------------------------------------------------------------------------------
    public static function upperCase(String $string) : Array
    {
        return preg_split('/(?=[A-Z])/', $string, -1, PREG_SPLIT_NO_EMPTY);
    }

    //--------------------------------------------------------------------------------------------------------
    // Apportion
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string  $string
    // @param numeric $length
    // @param string  $end
    //
    //--------------------------------------------------------------------------------------------------------
    public static function apportion(String $string, Int $length = 76, String $end = "\r\n") : String
    {
        $arrayChunk = array_chunk(preg_split("//u", $string, -1, PREG_SPLIT_NO_EMPTY), $length);

        $string = "";

        foreach( $arrayChunk as $chunk )
        {
            $string .= implode("", $chunk) . $end;
        }

        return $string;
    }

    /**
     * Divide
     * 
     * @param string $str       = NULL
     * @param string $separator = '|'
     * @param string $index     = '0'
     * @param string $count     = '1'
     */
    public static function divide(String $str = NULL, String $separator = '|', String $index = '0', String $count = '1')
    {
        $arrayEx = explode($separator, $str);

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
            $return = NULL;

            if( $count === 'all' )
            {
                $count = count($arrayEx) - $ind;
            }
            elseif( $count < 0 )
            {
                $count = count($arrayEx) + $count;
            }

            for( $i = 0; $i < $count; $i++ )
            {
                if( ! isset($arrayEx[$ind + $i]) )
                {
                    break;
                }
                
                $return .= $arrayEx[$ind + $i] . $separator;
            }

            return rtrim($return, $separator);
        }
    }
}
