<?php namespace ZN\Validation;

class HTMLTest extends \ZN\Test\GlobalExtends
{
    public function testMake()
    {
        $this->assertEquals('&lt;b&gt;foo&lt;/b&gt;', Validator::html('<b>foo</b>'));
    }  
}