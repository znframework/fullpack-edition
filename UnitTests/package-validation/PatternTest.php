<?php namespace ZN\Validation;

class PatternTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::pattern('1234', '/[0-9]+/'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::pattern('abc', '/[0-9]+/'));
    }  
}