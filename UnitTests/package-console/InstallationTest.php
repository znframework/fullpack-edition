<?php namespace ZN\Console;

use Buffer;

class InstallationTest extends \PHPUnit\Framework\TestCase
{
    public function testInstallation()
    {
        Buffer::callback(function()
        {
            new Installation('', []);
        });
    
    }
}