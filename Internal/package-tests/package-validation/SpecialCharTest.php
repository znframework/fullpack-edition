<?php namespace ZN\Validation;

class SpecialCharTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::specialChar('½#£abc'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::specialChar('abc'));
    }
}