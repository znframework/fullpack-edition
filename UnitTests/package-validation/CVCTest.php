<?php namespace ZN\Validation;

class CVCTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::cvc('978', 'maestro'));
    }  
    
    public function testInvalid()
    {
        $this->assertFalse(Validator::cvc('4978', 'visa'));
    }  
}