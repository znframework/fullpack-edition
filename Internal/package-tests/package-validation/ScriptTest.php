<?php namespace ZN\Validation;

class ScriptTest extends \ZN\Test\GlobalExtends
{
    public function testMake()
    {
        $this->assertEquals('&#60;script&#62;<b>foo</b>&#60;/script&#62;', Validator::script('<script><b>foo</b></script>'));
    }  
}