<?php namespace ZN;

class RevolvingTest extends ZerocoreExtends
{
    public function testCallstatic()
    {
        $this->assertIsObject($this->revolvingMock::run('Example'));
    }
}