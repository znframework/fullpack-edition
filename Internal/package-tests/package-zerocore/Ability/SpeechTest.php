<?php namespace ZN;

class SpeechTest extends ZerocoreExtends
{
    public function testCallStatic()
    {
        $this->assertFalse($this->speechMock::database());
    }

    public function testAll()
    {
        $this->assertIsArray($this->speechMock::all());
    }
}