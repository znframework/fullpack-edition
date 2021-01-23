<?php namespace ZN\Crontab;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Parameters
{
    /**
     * Magic Constructor
     * 
     * @param string $path
     * @param string & $command
     */
    public static function convert(Array $parameters)
    {
        return $parameters ? implode(',', array_map(function($data){ return '"'.$data.'"';}, $parameters)) : '';
    }
}
