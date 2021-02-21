<?php namespace ZN\Cache;

use Cache;

class SelectTest extends \PHPUnit\Framework\TestCase
{
    public function testSelect()
    {
        Cache::insert('example', 1);

        $this->assertEquals(1, Cache::select('example'));
    }

    public function testSelectCompress()
    {
        Cache::insert('example', 1, 1, 'gz');

        $this->assertEquals(1, Cache::select('example', 'gz'));
    }
}