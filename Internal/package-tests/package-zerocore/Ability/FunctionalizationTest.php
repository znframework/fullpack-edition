<?php namespace ZN;

class FunctionalizationTest extends ZerocoreExtends
{
    public function testFalse()
    {
        $this->assertFalse($this->functionalizationFalseMock->example());
    }
}