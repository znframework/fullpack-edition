<?php namespace ZN\Validation;

class XSSTest extends \ZN\Test\GlobalExtends
{
    public function testMake()
    {
        $this->assertEquals('&#60;b&#62;foo&#60;/b&#62;', Validator::xss('<b>foo</b>'));
    }  
}