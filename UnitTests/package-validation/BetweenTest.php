<?php namespace ZN\Validation;

class BetweenTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::between(10, 9, 11));
        $this->assertTrue(Validator::betweenBoth(10, 10, 11));
        $this->assertTrue(Validator::betweenBoth(10, 11, 10));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::between(10, 10, 11));
        $this->assertFalse(Validator::betweenBoth(10, 12, 13));
    }  
}