<?php namespace ZN\Validation;

class CardNumberTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::card('5555555555554444', 'maestro'));
    }  
    
    public function testInvalid()
    {
        $this->assertFalse(Validator::card('5555555555554444', 'visa'));
    }  

    public function testTypeDetected()
    {
        $this->assertTrue(Validator::card('5555555555554444'));
    } 
}