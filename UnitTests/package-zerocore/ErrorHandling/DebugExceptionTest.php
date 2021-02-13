<?php namespace ZN\ErrorHandling;

use Config;
use Buffer;

class DebugExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        $output = Buffer::callback(function()
        {
            Config::project('errorReporting', 0);
            
            new DebugException('Database', 'tableNotExistsError', 'table');
        });

        $this->assertEquals('', $output);  

        $output = Buffer::callback(function()
        {
            Config::project('errorReporting', 0);
            
            new DebugException('myMessage', 'tableNotExistsError', 'table');
        });

        $this->assertEquals('', $output);  

        Config::project('errorReporting', 1);
    }
}