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

class Search
{
    /**
     * Finds the expression between two statements.
     * 
     * @param string $str
     * @param string $start
     * @param string $end
     * @param bool   $case = true  [optional]
     * @param bool   $both = false [optional]
     * 
     * @return string|bool
     */
    public static function between(string $str, string $start, string $end, bool $case = true, bool $both = false)
    {
        if( preg_match('/(?<start>' . preg_quote($start, '/') . ')(?<search>.*?)(?<end>' . preg_quote($end, '/') . ')/' . ( $case === true ? NULL : 'i' ), $str, $match) )
        {
            if( $both === false )
            {
                return $match['search'];
            }
    
            return $match['start'] . $match['search'] . $match['end'];
        }
        
        return false;
    }

   /**
     * Finds the expression between two expressions, including parameters.
     * 
     * @param string $str
     * @param string $start
     * @param string $end
     * @param bool   $case = true  [optional]
     * 
     * @return string|bool
     */
    public static function betweenBoth(string $str, string $start, string $end, bool $case = true) 
    {
        return self::between($str, $start, $end, $case, true);
    }

    /**
     * Search 
     * 
     * @param string $str
     * @param string $needle
     * @param string $type = 'string' - options[string|position]
     * @param bool   $case = true
     */
    public static function use(string $str, string $needle, string $type = 'string', bool $case = true)
    {
        if( $type === 'string' )
        {
            if( $case === true )
            {
                $function = 'mb_strstr';
            }
            else
            {
                $function = 'mb_stristr'; // @codeCoverageIgnore
            }

            return $function($str, $needle);
        }

        if( $type === 'position' )
        {
            if( $case === true )
            {
                $function = 'mb_strpos';
            }
            else
            {
                $function = 'mb_stripos';
            }

            return $function($str, $needle);
        }

        return false; // @codeCoverageIgnore
    }

    /**
     * Search Position 
     * 
     * @param string $str
     * @param string $needle
     * @param bool   $case = true
     */
    public static function position(string $str, string $needle, bool $case = true)
    {
        return self::use($str, $needle, __FUNCTION__, $case);
    }

    /**
     * Search String 
     * 
     * @param string $str
     * @param string $needle
     * @param bool   $case = true
     */
    public static function string(string $str, string $needle, bool $case = true) : string
    {
        return self::use($str, $needle, __FUNCTION__, $case);
    }
}
