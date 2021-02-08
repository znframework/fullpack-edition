<?php namespace ZN\Cache;

use Cache;

class CodeTest extends \PHPUnit\Framework\TestCase
{
    public function testCode()
    {
        $result = Cache::code(function()
        {
            echo 10;
        });

        $this->assertEquals(10, $result);
    }

    public function testCodeWithKey()
    {
        $result = Cache::key('example')->code(function()
        {
            echo 10;
        });

        $this->assertEquals(10, $result);
    }
}