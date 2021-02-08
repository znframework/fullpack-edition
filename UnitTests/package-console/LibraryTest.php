<?php namespace ZN\Console;

use Buffer;

class LibraryTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        Buffer::callback(function()
        {
            new Library('Encode:super', [1]);
        });
    
    }
}