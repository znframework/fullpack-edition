<?php namespace ZN\Validation;

class MatchPasswordTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::matchPassword('1234', '1234'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::matchPassword('1234', '12345'));
    }  
}