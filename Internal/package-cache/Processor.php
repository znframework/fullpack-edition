<?php namespace ZN\Cache;
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
use ZN\Inclusion;
use ZN\Ability\Driver;
use ZN\Helpers\Converter;

class Processor implements ProcessorInterface
{
    use Driver;

    /**
     * Driver settings
     * 
     * @param array  options
     * @param string namespace
     */
    const driver =
    [
        'options'   => ['file', 'apc', 'memcache', 'redis'],
        'namespace' => 'ZN\Cache\Drivers',
        'config'    => 'Storage:cache',
        'default'   => 'ZN\Cache\CacheDefaultConfiguration'
    ];
    
    protected $codeCount = 0;
    protected $refresh   = false;
    protected $key       = NULL;

    /**
     * Refresh cache
     * 
     * @param void
     * 
     * @return Cache
     */
    public function refresh()
    {
        $this->refresh = true;

        return $this;
    }

    /**
     * Set data
     * 
     * @param array $data = NULL
     * 
     * @return Cache
     */
    public function data(array $data = NULL)
    {
        Inclusion\Properties::data($data);

        return $this;
    }

    /**
     * Set key
     * 
     * @param string $key = NULL
     * 
     * @return Cache
     */
    public function key(string $key = NULL) : Processor
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Cache code
     * 
     * @param callable $function
     * @param mixed    $time       = 60
     * @param string   $compressed = 'gz'
     * 
     * @return string
     */
    public function code(callable $function, $time = 60, string $compress = 'gz') : string
    {
        $this->codeCount++;

        if( $this->key === NULL )
        {
            $name = 'code-' . $this->codeCount . '-' . CURRENT_CONTROLLER . '-' . CURRENT_CFUNCTION;
        }
        else
        {
            $name = $this->key;

            $this->key = NULL;
        }

        $this->refreshCacheData($name);

        if( ! $select = $this->select($name, $compress) )
        {
            $output = Buffering\Callback::do($function);

            $this->insert($name, $output, $time, $compress);

            return $output;
        }
        else
        {
            return $select;
        }
    }

    /**
     * Cache view
     * 
     * @param string $file
     * @param mixed  $time     = 60
     * @param string $compress = 'gz'
     * 
     * @return string
     */
    public function view(string $file, $time = 60, string $compress = 'gz') : string
    {
        return $this->file($file, $time, $compress, 'view');
    }

    /**
     * Cache file
     * 
     * @param string $file
     * @param mixed  $time     = 60
     * @param string $compress = 'gz'
     * 
     * @return string
     */
    public function file(string $file, $time = 60, string $compress = 'gz', $type = 'something') : string
    {
        $name = Converter::slug($file);

        $this->refreshCacheData($name);

        if( ! $select = $this->select($name, $compress) )
        {
            Inclusion\Properties::usable();

            if( $type === 'shomething' )
            {
                $output = Inclusion\Something::use($file);
            }
            else
            {
                $output = Inclusion\View::use($file);
            }

            $this->insert($name, $output, $time, $compress);

            return $output;
        }
        else
        {
            return $select; // @codeCoverageIgnore
        }
    }

    /**
     * Select key
     * 
     * @param string $key
     * @param mixed  $compressed = false
     * 
     * @return mixed
     */
    public function select(string $key, $compressed = false)
    {
        return $this->driver->select($key, $compressed);
    }

    /**
     * Insert key
     * 
     * @param string $key
     * @param mixed  $var
     * @param mixed  $time       = 60
     * @param mixed  $compressed = false
     * 
     * @return bool
     */
    public function insert(string $key, $var, $time = 60, $compressed = false) : bool
    {
        if( ! preg_match('/(?<count>[0-9]+)\s*(?<type>second|minute|hour|day|week|month|year)*s*/', $time, $match) )
        {
            throw new Exception\InvalidTimeException(NULL, $time);
        }

        $time = Converter::time($match['count'], $match['type'] ?? 'second', 'second');

        return $this->driver->insert($key, $var, $time, $compressed);
    }

    /**
     * Delete key
     * 
     * @param string $key
     * 
     * @return bool
     */
    public function delete(string $key) : bool
    {
        return $this->driver->delete($key);
    }

    /**
     * Increment key
     * 
     * @param string $key
     * @param int    $increment = 1
     * 
     * @return int
     */
    public function increment(string $key, int $increment = 1) : int
    {
        return $this->driver->increment($key, $increment);
    }

    /**
     * Decrement key
     * 
     * @param string $key
     * @param int    $decrement = 1
     * 
     * @return int
     */
    public function decrement(string $key, int $decrement = 1) : int
    {
        return $this->driver->decrement($key, $decrement);
    }

    /**
     * Clean all cache
     * 
     * @param void
     * 
     * @return bool
     */
    public function clean() : bool
    {
        return $this->driver->clean();
    }

    /**
     * Get info
     * 
     * @param mixed $type
     * 
     * @return array
     */
    public function info($type = NULL) : array
    {
        return $this->driver->info($type);
    }

    /**
     * Destructor magic method
     */
    public function __destruct()
    {
        $this->driver->close();
    }

    /**
     * protected refresh
     * 
     * @param string $data
     * 
     * @return void
     */
    protected function refreshCacheData($data)
    {
        if( $this->refresh === true )
        {
            $this->delete($data);
            $this->refresh = false;
        }
    }
}
