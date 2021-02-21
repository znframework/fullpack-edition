<?php namespace ZN\Validation;

class MincharTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::minchar('abcd', 3));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::minchar('abcd', 5));
    }
}