<?php namespace ZN;

class SupportTest extends ZerocoreExtends
{
    public function testFunction()
    {
        try
        {
            Support::function('example_func', 'value');
        }
        catch( Exception $e )
        {
            $this->assertEquals('Error: Call to undefined function `value`!', $e->getMessage());
        }
    }

    public function testCallback()
    {
        try
        {
            Support::callback('example_func', 'value');
        }
        catch( Exception $e )
        {
            $this->assertEquals('Error: Call to undefined function `value`!', $e->getMessage());
        }
    }

    public function testDriver()
    {
        try
        {
            Support::driver(['x'], 'y');
        }
        catch( Exception $e )
        {
            $this->assertEquals('`y` driver not found!', $e->getMessage());
        }
    }

    public function testExtension()
    {
        try
        {
            $this->supportMock->mockLoaded(false, 'value', function($param){ return $param; }, ['errkey' => 'errval']);
        }
        catch( Exception $e )
        {
            $this->assertEquals('errkey', $e->getMessage());
        }
    }
}