<?php namespace ZN;

class DriverTest extends ZerocoreExtends
{
    public function testParentConstructor()
    {
        $this->driverMock;
    }

    public function testetNullDefaultDriverName()
    {
        $this->assertEquals('NULL', $this->driverMock->mockSetNullDefaultDriverName());
    }
}