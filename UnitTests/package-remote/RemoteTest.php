<?php namespace ZN\Remote;

class RemoteTest extends \PHPUnit\Framework\TestCase
{
    public function testExec()
    {
        $this->assertIsObject(new Remote);
    }
}