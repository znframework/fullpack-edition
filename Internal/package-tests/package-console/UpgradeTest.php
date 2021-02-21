<?php namespace ZN\Console;

use Buffer;

class UpgradeTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        Buffer::callback(function()
        {
            new Upgrade;
            new UndoUpgrade('last');
        });
    
    }
}