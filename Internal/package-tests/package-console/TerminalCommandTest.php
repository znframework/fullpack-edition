<?php namespace ZN\Console;

use Buffer;

class TerminalCommandTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        Buffer::callback(function()
        {
            new TerminalCommand('php -v');
        });
    
    }
}