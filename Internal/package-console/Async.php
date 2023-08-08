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

use Throwable;
use ZN\Config;
use ZN\Buffering;
use ZN\Protection\Json;
use ZN\ErrorHandling\Errors;
use ZN\ErrorHandling\Exceptions;

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
     * Get process Id.
     */
    public static function getProcId() : string
    {
        return self::$procId;
    }

    /**
     * Run command
     * 
     * @param string $command
     * @param array  $data = []
     */
    public static function run(string $command, array $data = []) : string
    {
        self::$procId = $procId = self::$procDir . uniqid();

        $processor = Config::default('ZN\Prompt\PromptDefaultConfiguration')::get('Services', 'processor');

        if( ! file_exists($processor['path']) )
        {
            $path = 'php';
        }
        else
        {
            $path = $processor['path'];
        }

        $open = proc_open($path . ' zerocore ' . $command . ' "' . $procId . '"', [], $arr);

        $data['status'] = proc_get_status($open);
        
        file_put_contents($procId, Json::encode($data));

        return $procId;
    }

    /**
     * Command process
     * 
     * @param string   $procId
     * @param callback $callable
     * @param bool     $displayError = false
     */
    public static function process(string $procId, callable $callable, bool $displayError = false) : void
    {
        self::$procId = $procId;

        $data = self::getData($procId);

        try
        {
            $callable($data, $procId);
        }
        catch( Throwable $e )
        {
            if( $displayError )
            {
                $error = 
                [
                    'code'    => $e->getCode(),
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                    'trace'   => $e->getTrace()
                ];

                self::report($error, 'error');
            }
        }

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
    public static function report(array $data, string $suffix = 'report') : int
    {
        return file_put_contents(self::$procId . '-' . $suffix, Json::encode($data));
    }

    /**
     * Status
     * 
     * @param string ...$procIds
     * 
     * @return array
     */
    public static function status(string ...$procIds) : array
    {
        $pending = [];
        
        foreach( $procIds as $procId )
        {
            if( is_file($procId) )
            {
                $pending[$procId] = 1; # pending.
            }
        }

        return $pending;
    }

    /**
     * Is finish
     * 
     * @param string ...$procIds
     * 
     * @return bool
     */
    public static function isFinish(string ...$procIds) : bool
    {
        return  ! in_array(1, self::status(...$procIds));
    }

    /**
     * Dispay Report
     * 
     * @param string $errorFile
     * 
     * @return string
     */
    public static function displayError(string $errorFile) : string
    {
        if( $fileContent = file_get_contents(self::$procDir . $errorFile) ) 
        {
            $data = Json::decodeArray($fileContent);

            $display = Buffering\Callback::do(function() use($data)
            { 
                Exceptions::table($data['code'] ?? NULL, $data['message'], $data['file'], $data['line'], $data['trace']); 
            });
        }
        else
        {
            $display = Errors::message('File not found!');
        }
        
        return $display;
    }
}
