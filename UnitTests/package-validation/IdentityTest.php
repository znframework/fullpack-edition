<?php namespace ZN\Validation;

class IdentityTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::identity('45190635250'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::identity('2342423'));
    }

    public function testInvalidStartZero()
    {
        $this->assertFalse(Validator::identity('05190635250'));
    }
}