<?php namespace ZN\Console;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Protection\Json;

/**
 * @codeCoverageIgnore
 */
class Async
{
    /**
     * Keeps process ID.
     * 
     * @var string $procId
     */
    protected static $procId = '';

    /**
     * Keeps process directory.
     * 
     * @var string $procDir
     */
    protected static $procDir = FILES_DIR;

    /**
     * Set process directory.
     * 
     * @param string $directory
     * 
     * @return self
     */
    public static function setProcDirectory(string $directory) : Async
    {
        self::$procDir = $directory;

        return new self;
    }

    /**
     * Get process data.
     * 
     * @param string $procId
     * 
     * @return array
     */
    public static function getData(string $procId) : array
    {
        $return = Json::decodeArray(file_get_contents($procId));

        return $return;
    }

    /**
     * Run command
     * 
     * @param string $command
     * @param array  $data = []
     */
    public static function run(string $command, array $data = []) : void
    {
        $procId = self::$procDir . uniqid();

        file_put_contents($procId, Json::encode($data));

        proc_open('php zerocore ' . $command . ' "' . $procId . '"', [], $arr);
    }

    /**
     * Command process
     * 
     * @param string $procId
     * @param callback $callable
     */
    public static function process(string $procId, callable $callable) : void
    {
        self::$procId = $procId;

        $data = self::getData($procId);

        $callable($data, $procId);

        self::remove($procId);
    }
    
    /**
     * Remove process ID
     * 
     * @param string $procId
     * 
     * @return bool
     */
    public static function remove(string $procId) : bool
    {
        if( is_file($procId) )
        {
            return unlink($procId);
        }

        return false;
    }
    
    /**
     * Create report
     * 
     * @param array $data
     * 
     * @return int
     */
    public static function report(array $data) : int
    {
        return file_put_contents(self::$procId . '-report', Json::encode($data));
    }
}
