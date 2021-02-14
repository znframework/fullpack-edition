<?php namespace ZN\Comparison;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class MemoryUsage
{
    /**
     * Calculate memory
     * 
     * @param string $result
     * 
     * @return float
     */
    public static function calculate(String $result) : Float
    {
        $resend  = $result."_end";
        $restart = $result."_start";

        if( ! isset(Properties::$memtests[$restart]) )
        {
            throw new Exception\InvalidParameterException(NULL, ['&' => 'calculatedMemory', '%' => $result, 'start']); // @codeCoverageIgnore      
        }

        if( ! isset(Properties::$memtests[$resend]) )
        {
            throw new Exception\InvalidParameterException(NULL, ['&' => 'calculatedMemory', '%' => $result, 'end']); // @codeCoverageIgnore
        }

        return Properties::$memtests[$resend] - Properties::$memtests[$restart];
    }

    /**
     * Usage memory
     * 
     * @param bool $realMemory = false
     * 
     * @return int
     */
    public static function normal(Bool $realMemory = false) : Int
    {
        return  memory_get_usage($realMemory);
    }

    /**
     * Usage max memory
     * 
     * @param bool $realMemory = false
     * 
     * @return int
     */
    public static function maximum(Bool $realMemory = false) : Int
    {
        return  memory_get_peak_usage($realMemory);
    }
}
