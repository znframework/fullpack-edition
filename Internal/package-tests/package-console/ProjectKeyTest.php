<?php namespace ZN\Console;

use Buffer;

class ProjectKeyTest extends \PHPUnit\Framework\TestCase
{
    public function testProjectKey()
    {
        Buffer::callback(function()
        {
            new GenerateProjectKey('key');
        });
    
    }
}