<?php namespace ZN\Console;

use Buffer;

class MethodTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        Buffer::callback(function()
        {
            new Method('strtoupper', ['abc']);
        });
    
    }
}