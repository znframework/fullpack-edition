<?php namespace ZN\Validation;

class AlphaTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::alpha('abc'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::alpha('ab14'));
    }
}