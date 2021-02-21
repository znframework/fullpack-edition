<?php namespace ZN\Validation;

class TrimTest extends \ZN\Test\GlobalExtends
{
    public function testMake()
    {
        $this->assertEquals('abc', Validator::trim(' abc '));
    }  
}