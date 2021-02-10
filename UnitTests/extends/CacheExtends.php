<?php namespace ZN\Cache;

use Cache;
use Config;

class CacheExtends extends \ZN\Test\GlobalExtends
{
    public function __construct()
    {
        parent::__construct();

        Config::storage('cache', 
        [
            'driver'         => 'file',
            'driverSettings' =>
            [
                'memcache' =>
                [
                    'host'   => '127.0.0.2',
                    'port'   => '11211',
                    'weight' => '1',
                ],
                'redis' =>
                [
                    'password' => 'zntest',
                    'host'     => '127.0.0.1',
                    'port'     => 6379,
                    'timeout'  => 0
                ]
            ]
        ]);    
    }

    public function redis()
    {
        return Cache::driver('redis');
    }

    public function memcache()
    {
        return Cache::driver('memcache');
    }
}