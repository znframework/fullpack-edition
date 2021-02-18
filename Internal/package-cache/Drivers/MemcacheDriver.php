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

use Memcached;
use ZN\Support;
use ZN\ErrorHandling\Errors;
use ZN\Cache\DriverMappingAbstract;
use ZN\Cache\Exception\UnsupportedDriverException;

class MemcacheDriver extends DriverMappingAbstract
{
    /**
     * Magic constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct(Array $settings = NULL)
    {
        parent::__construct();
        
        Support::library('Memcached', 'Memcache');

        $this->memcache = new Memcached;

        $config = $this->config['driverSettings'];

        $config = ! empty($settings)
                  ? $settings
                  : $config['memcache'];
        
        $connect = $this->memcache->addServer($config['host'], $config['port'], $config['weight']);
        
        if( empty($connect) )
        {
            throw new UnsupportedDriverException(NULL, 'Memcache');
        }

        return true;
    }

    /**
     * Select key
     * 
     * @param string $key
     * @param mixed  $compressed
     * 
     * @return mixed
     */
    public function select($key, $compressed = NULL)
    {
        return $this->memcache->get($key);
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
    public function insert($key, $var, $time, $compressed)
    {
        return $this->memcache->set($key, $var, $time);
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
        return $this->memcache->delete($key);
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
        return $this->memcache->increment($key, $increment);
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
        return $this->memcache->decrement($key, $decrement);
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
        return $this->memcache->flush();
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
        return $this->memcache->getStats() ?: [];
    }
}
