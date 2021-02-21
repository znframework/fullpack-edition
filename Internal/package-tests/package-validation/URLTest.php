<?php namespace ZN\Validation;

class URLTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::url('https://www.znframework.com'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::url('www.znframework.com'));
    }
}