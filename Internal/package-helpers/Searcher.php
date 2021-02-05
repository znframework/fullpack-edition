<?php namespace ZN\Helpers;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Searcher
{
    /**
     * Search Data
     * 
     * @param mixed  $searchData
     * @param mixed  $searchWord
     * @param string $output = 'boolean' - options[string|position|boolean]
     */
    public static function data($searchData, $searchWord, String $output = 'boolean')
    {
        if( ! is_array($searchData) )
        {   
            switch( $output )
            {
                case 'string'  : return strstr($searchData, $searchWord);
                case 'position': return strpos($searchData, $searchWord);
                case 'boolean' : return strpos($searchData, $searchWord) > -1 ? true : false;
                default        : return false;
            }
        }
        else
        {
            $result = array_search($searchWord, $searchData);

            switch( $output )
            {
                case 'string'  : return $result ? $searchWord : false;
                case 'position': return $result ?: -1;
                case 'boolean' : return (bool) $result;
                default        : return false;
            }
        }
    }  
}
