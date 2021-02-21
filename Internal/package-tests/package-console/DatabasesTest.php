<?php namespace ZN\Console;

use Buffer;

class DatabasesTest extends \PHPUnit\Framework\TestCase
{
    public function testDatabases()
    {
        Buffer::callback(function()
        {
            new GenerateDatabases;
        });
    
    }
}