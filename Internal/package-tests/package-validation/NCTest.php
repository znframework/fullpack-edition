<?php namespace ZN\Validation;

class NCTest extends \ZN\Test\GlobalExtends
{
    public function testMake()
    {
        $this->assertEquals('[badchars]abc[badchars]', Validator::nc('<abc>'));
    }  
}