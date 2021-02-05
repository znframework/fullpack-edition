<?php namespace ZN\Validation;

class RequiredTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::required(0));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::required(''));
    }  
}