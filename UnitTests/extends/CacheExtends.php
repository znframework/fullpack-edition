<?php namespace ZN\Cache;

class CacheExtends extends \ZN\Test\GlobalExtends
{
    public function redis()
    {
        return new Drivers\RedisDriver
        ([
            'password' => 'zntest',
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'timeout'  => 0
        ]);
        
    }
}