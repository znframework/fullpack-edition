<?php namespace ZN\Cache\Drivers;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Support;
use ZN\Singleton;
use ZN\Filesystem;
use ZN\Cache\DriverMappingAbstract;

class FileDriver extends DriverMappingAbstract
{
    /**
     * Keeps path
     * 
     * @var string
     */
    protected $path = STORAGE_DIR . 'Cache/';

    /**
     * Keeps compression class
     * 
     * @var object
     */
    protected $compress;


    /**
     * Magic constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->createCacheDirectoryIfNotExists();

        Support::writable($this->path);

        $this->compress = Singleton::class('ZN\Compression\Force');
    }

    /**
     * Select key
     * 
     * @param string $key
     * @param mixed  $compressed
     * 
     * @return mixed
     */
    public function select($key, $compressed)
    {
        $data = $this->selectCacheKey($key);

        if( ! empty($data['data']) )
        {
            if( $compressed !== false )
            {
                $data['data'] = $this->compress->driver($compressed)->undo($data['data']);
            }

            return $data['data'];
        }

        return false;
    }

    /**
     * Insert key
     * 
     * @param string $key
     * @param mixed  $var
     * @param int    $time
     * @param mixed  $compressed
     * 
     * @return bool
     */
    public function insert($key, $var, $time, $compressed = false)
    {
        if( $compressed !== false )
        {
            $var = $this->compress->driver($compressed)->do($var);
        }

        $datas =
        [
            'time'  => time(),
            'ttl'   => $time,
            'data'  => $var
        ];

        if( file_put_contents($pathKey = $this->path . $key, serialize($datas)) )
        {
            chmod($pathKey, 0640);

            return true;
        }

        return false; // @codeCoverageIgnore
    }

    /**
     * Delete key
     * 
     * @param string $key
     * 
     * @return bool
     */
    public function delete($key)
    {
        if( is_file($path = $this->path . $key) )
        {
            return unlink($path);
        }
        
        return false;
    }

    /**
     * Increment key
     * 
     * @param string $key
     * @param int    $increment = 1
     * 
     * @return int
     */
    public function increment($key, $increment)
    {
        $data = $this->selectCacheKey($key);

        if( $data === false )
        {
            $data = ['data' => 0, 'ttl' => 60];
        }
        elseif( ! is_numeric($data['data']) )
        {
            return false; // @codeCoverageIgnore
        }

        $newValue = $data['data'] + $increment;

        return ( $this->insert($key, $newValue, $data['ttl']) )
               ? $newValue
               : false;
    }

    /**
     * Decrement key
     * 
     * @param string $key
     * @param int    $decrement = 1
     * 
     * @return int
     */
    public function decrement($key, $decrement)
    {
        $data = $this->selectCacheKey($key);

        if( $data === false )
        {
            $data = ['data' => 0, 'ttl' => 60];
        }
        elseif( ! is_numeric($data['data']) )
        {
            return false; // @codeCoverageIgnore
        }

        $newValue = $data['data'] - $decrement;

        return $this->insert($key, $newValue, $data['ttl'])
               ? $newValue
               : false;
    }

    /**
     * Clean all cache
     * 
     * @param void
     * 
     * @return bool
     */
    public function clean()
    {
        foreach( Filesystem\Folder::files($this->path, NULL, true) as $file )
        { 
            if( is_file($file) )
            {
                unlink($file);
            }
        }

        return true;
    }

    /**
     * Get info
     * 
     * @param mixed $type
     * 
     * @return array
     */
    public function info($type = NULL)
    {
        $info = Filesystem\Info::fileInfo($this->path);

        if( $type === NULL )
        {
            return $info;
        }
        elseif( ! empty($info[$type]) )
        {
            return $info[$type];
        }

        return [];
    }

    /**
     * Protected create cache directory if not exists
     */
    protected function createCacheDirectoryIfNotExists()
    {
        if( ! is_dir($this->path) )
        {
            mkdir($this->path, 0755); // @codeCoverageIgnore
        }
    }

    /**
     * protected select key
     * 
     * @param string $key
     * 
     * @return mixed
     */
    protected function selectCacheKey($key)
    {
        if( ! file_exists($pathKey = $this->path . $key) )
        {
            return false;
        }

        $data = unserialize(file_get_contents($pathKey));

        if( $data['ttl'] > 0 && time() > $data['time'] + $data['ttl'] )
        {
            unlink($pathKey); // @codeCoverageIgnore

            return false; // @codeCoverageIgnore
        }

        return $data;
    }
}
