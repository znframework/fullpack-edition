<?php namespace ZN\Cache;

use Cache;

class InsertTest extends \PHPUnit\Framework\TestCase
{
    public function testInsert()
    {
        Cache::insert('example', 1);

        $this->assertEquals(1, Cache::select('example'));
    }

    public function testInsertTimeException()
    {
        try
        {
            Cache::insert('newData', 1, 'invalid date');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}