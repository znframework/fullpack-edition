<?php namespace ZN\Helpers;

use Buffer;
use Debugger;

class DebuggerTest extends \PHPUnit\Framework\TestCase
{
    public function testCurrent()
    {
        $this->assertIsObject(Debugger::current());
    }

    public function testParent()
    {
        $this->assertStringEndsWith('DebuggerTest.php', Debugger::parent()->file);

        $this->assertStringEndsWith('TestCase.php', Debugger::parent(2)->file);
    }

    public function testOutput()
    {
        $this->assertIsObject(Debugger::output(1));
    }

    public function testOutputArray()
    {
        $this->assertIsArray(Debugger::output('array'));
    }

    public function testOutputString()
    {
        $this->assertIsString(Buffer::callback(function(){Debugger::output('string');}));
    }

    public function testOutputDefault()
    {
        $this->assertIsObject(Debugger::output('abc'));
    }
}