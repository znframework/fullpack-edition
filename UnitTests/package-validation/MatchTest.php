<?php namespace ZN\Validation;

class MatchTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::match('1234', '1234'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::match('1234', '12345'));
    }  
}