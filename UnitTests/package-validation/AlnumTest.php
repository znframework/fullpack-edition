<?php namespace ZN\Validation;

class AlnumTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::alnum('ab14'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::alnum('ab14-'));
    }
}