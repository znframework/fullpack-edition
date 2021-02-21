<?php namespace ZN;

class StaticAccessTest extends ZerocoreExtends
{
    public function testCall()
    {
        $this->assertEquals('param', $this->staticAccessMock->run('param'));
    }
}