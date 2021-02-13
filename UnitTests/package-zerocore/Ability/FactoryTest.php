<?php namespace ZN;

class FactoryTest extends ZerocoreExtends
{
    public function testRun()
    {
        try
        {
            $this->factoryInvalidClassMock->read('robots.txt');
        }
        catch( \Exception $e )
        {
            $this->assertStringContainsString('[12345::read] parameter contains invalid factory method!', $e->getMessage());
        }
    }

    public function testFalse()
    {
        $this->assertFalse($this->factoryFalseMock->example());
    }

    public function testInvalidMethod()
    {
        try
        {
            $this->factoryInvalidMethodMock->example();
        }
        catch( \Exception $e )
        {
            $this->assertStringContainsString('Call to undefined function', $e->getMessage());
        }
    }
}