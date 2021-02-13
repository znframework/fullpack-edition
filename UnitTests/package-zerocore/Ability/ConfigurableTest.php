<?php namespace ZN;

class ConfigurableTest extends ZerocoreExtends
{
    public function testCallStatic()
    {
        $this->configurableMock::errors(['a' => 1]);

        $this->assertEquals(['a' => 1], $this->configurableMock::errors());
    }

    public function testAll()
    {
        $this->assertEquals(['errors' => ['a' => 1]], $this->configurableMock::all());
    }
}