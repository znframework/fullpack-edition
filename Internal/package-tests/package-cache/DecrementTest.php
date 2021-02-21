<?php namespace ZN\Cache;

use Cache;

class DecrementTest extends \PHPUnit\Framework\TestCase
{
    public function testDecrement()
    {
        Cache::insert('a', 2);

        Cache::decrement('a');

        $this->assertEquals(1, Cache::select('a'));
    }

    public function testDecrementEmpty()
    {
        Cache::decrement('testDecrementEmpty');

        $this->assertEquals(-1, Cache::select('testDecrementEmpty'));
    }
}