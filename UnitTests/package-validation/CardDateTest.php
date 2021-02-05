<?php namespace ZN\Validation;

class CardDateTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::cardDate('10/30'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::cardDate('1030'));
    }
}