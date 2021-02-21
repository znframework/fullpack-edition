<?php namespace ZN\Console;

use Buffer;

class ResultTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        Buffer::callback(function()
        {
            new Result('Hello', 'Hello Title');
            new Result(['Hello', 'Hi hello'], 'Hello Title');
        });
    }
}