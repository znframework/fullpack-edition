<?php namespace ZN;

class BaseControllerTest extends ZerocoreExtends
{
    public function testCallStatic()
    {
        $this->assertEquals('c4ca4238a0b923820dcc509a6f75849b', $this->baseControllerMock->encode->type('1'));
    }

    public function testReload()
    {
        $this->assertIsObject($this->baseControllerMock->reload());
    }
}