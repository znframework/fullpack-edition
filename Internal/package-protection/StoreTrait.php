<?php namespace ZN\Protection;
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
use ZN\Protection\Exception\ScalarDataException;

trait StoreTrait
{
    protected static function addExtension($file)
    {
        if( strstr(get_called_class(), 'Json') )
        {
            return Base::suffix($file, '.json');
        }

        return $file;
    }

    /**
     * Write
     * 
     * @param string $file
     * @param mixed  $data
     * 
     * @return string
     */
    public static function write(string $file, $data) : bool
    {
        if( is_scalar($data) )
        {
            throw new ScalarDataException(NULL, 'data');
        }

        $json = self::encode($data);

        return file_put_contents(self::addExtension($file), $json);
    }

    /**
     * Read
     * 
     * @param string $file
     * @param bool   $array = false
     * 
     * @return mixed
     */
    public static function read(string $file, bool $array = false)
    {
        $file = self::addExtension($file);

        if( ! is_file($file) )
        {
            return false;
        }

        $json = file_get_contents($file);

        return self::decode($json, $array);
    }

    /**
     * Read object
     * 
     * @param string $file
     * 
     * @return mixed
     */
    public static function readObject(string $file)
    {
        return self::read($file, false);
    }

    /**
     * Read array
     * 
     * @param string $file
     * 
     * @return mixed
     */
    public static function readArray(string $file) : array
    {
        if( is_file($file) && strstr(get_called_class(), 'Separator') )
        {
            return (array) self::decode(file_get_contents($file));
        }

        return self::read($file, true);
    }
}
