<?php namespace ZN;

class ContainerTest extends ZerocoreExtends
{
    public function testCallStatic()
    {
        $this->assertEquals(1, $this->containerMock::run());

        try
        {
            $this->containerMock::runx();
        }
        catch( \Exception $e )
        {
            $this->assertStringContainsString('method is undefined!', $e->getMessage());
        }
    }

    public function testDriver()
    {
        try
        {
            $this->containerMock::driver('driverClass');
        }
        catch( \Exception $e )
        {
            $this->assertStringContainsString('driver is not a valid driver for class', $e->getMessage());
        }
    }
}