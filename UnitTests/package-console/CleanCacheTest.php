<?php namespace ZN\Console;

use Cache;
use Buffer;

class CleanCacheTest extends \PHPUnit\Framework\TestCase
{
    public function testCleanCache()
    {
        Cache::insert('a', 'value');

        Buffer::callback(function()
        {
            new CleanCache;
        });
    
        $this->assertEmpty(Cache::select('a'));
    }
}