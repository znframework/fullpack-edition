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

use Redis;
use RedisException;
use ZN\Support;
use ZN\ErrorHandling\Errors;
use ZN\Cache\Exception\ConnectionRefusedException;
use ZN\Cache\Exception\AuthenticationFailedException;
use ZN\Cache\DriverMappingAbstract;

/**
 * @codeCoverageIgnore
 */
class RedisDriver extends DriverMappingAbstract
{
    /**
     * Keeps redis class
     * 
     * @var Redis
     */
    protected $redis;

    /**
     * Magic constructor
     * 
     * @param array $settings = NULL
     * 
     * @return void
     */
    public function __construct(array $settings = NULL)
    {
        parent::__construct();
        
        Support::extension('redis');

        $config = $settings ?: $this->config['driverSettings']['redis'];

        $this->redis = new Redis();

        try
        {
            $success = $this->redis->connect($config['host'], $config['port'], $config['timeout']);

            if ( empty($success) )
            {
                throw new ConnectionRefusedException(NULL, 'Connection');
            }
        }
        catch( RedisException $e )
        {
            throw new ConnectionRefusedException(NULL, $e->getMessage());
        }

        if ( $config['password'] && ! $this->redis->auth($config['password']) )
        {
            throw new AuthenticationFailedException; // @codeCoverageIgnore
        }
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
        return $this->redis->get($key);
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
    public function insert($key, $data, $time, $compressed)
    {
        return $this->redis->set($key, $data, $time);
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
        return $this->redis->delete($key);
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
        return $this->redis->incr($key, $increment);
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
        return $this->redis->decr($key, $decrement);
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
        return $this->redis->flushDB();
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
        return $this->redis->info();
    }

    /**
     * Close
     */
    public function close()
    {
        $this->redis->close();
    }
}
