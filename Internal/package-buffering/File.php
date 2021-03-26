<?php namespace ZN\Buffering;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Buffering;

class File
{
    /**
     * Buffer file
     * 
     * @param string $randomBufferClassPagePath
     * @param array  $randomBufferClassDataVariable
     * 
     * @return string
     */
    public static function do(string $randomBufferClassPagePath, array $randomBufferClassDataVariable = NULL) : string
    {
        if( ! is_file($randomBufferClassPagePath) )
        {
            throw new Exception\InvalidFileParameterException(NULL, '1.');
        }

        return Buffering::file($randomBufferClassPagePath, $randomBufferClassDataVariable);
    }
}
