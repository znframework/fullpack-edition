<?php namespace ZN\Console;

use Buffer;

class ShortCommandTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        Buffer::callback(function()
        {
            new CreateCommand('Example');
            new ShortCommand('Example:run a b c');
            new DeleteCommand('Example');
        });
    }
}