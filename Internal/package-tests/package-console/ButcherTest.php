<?php namespace ZN\Console;

use Buffer;

class ButcherTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        Buffer::callback(function()
        {
            new RunButcher('Default', 'project');
            new RunButcherDelete('Default', 'project');
            new ExtractButcher('all', 'ME');
            new ExtractButcherForce('all', 'ME');
            new ExtractButcherDelete('all', 'ME');
        });
    
    }
}