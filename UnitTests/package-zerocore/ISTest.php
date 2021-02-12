<?php namespace ZN;

class ISTest extends ZerocoreExtends
{
    public function testCall()
    {
        $this->assertTrue(\IS::alpha('abc'));
        $this->assertFalse(\IS::post('abc'));
        $this->assertFalse(\IS::file('abc'));
        try
        {
            \IS::unknown('abc');
        }
        catch( Exception $e )
        {
            $this->assertEquals('Error: Call to undefined function `IS::unknown()`!', $e->getMessage());
        }
    }

    public function testSoftware()
    {
        $this->assertIsString(IS::software());
    }

    public function testDeclaredClass()
    {
        $this->assertTrue(IS::declaredClass('ZN\Database\DB'));
    }
}