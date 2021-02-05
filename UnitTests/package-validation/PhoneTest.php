<?php namespace ZN\Validation;

class PhoneTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::phone('12+34', '**+**'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::phone('1234', '**-**'));
    }
}