<?php namespace ZN\Validation;

class MaxcharTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::maxchar('abcd', 5));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::maxchar('abcd', 3));
    }
}