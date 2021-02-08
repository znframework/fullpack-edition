<?php namespace ZN\Cache;

use Cache;

class IncrementTest extends \PHPUnit\Framework\TestCase
{
    public function testIncrement()
    {
        Cache::insert('a', 1);

        Cache::increment('a');

        $this->assertEquals(2, Cache::select('a'));
    }

    public function testIncrementEmpty()
    {
        Cache::increment('testIncrementEmpty');

        $this->assertEquals(1, Cache::select('testIncrementEmpty'));
    }
}