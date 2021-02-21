<?php namespace ZN\Validation;

class InjectionTest extends \ZN\Test\GlobalExtends
{
    public function testMake()
    {
        $this->assertEquals('<b>foo\"</b>', Validator::injection('<b>foo"</b>'));
    }  
}