<?php namespace ZN\Validation;

class PatternTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::pattern('1234', '/[0-9]+/'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::pattern('abc', '/[0-9]+/'));
    }  

    public function testRulesValid()
    {
        \Post::data('1234');

        $data = new Data;

        $data->pattern('[0-9]+')->rules('data');

        $this->assertEmpty($data->error('string')); 
    }

    public function testRulesInvalid()
    {
        \Post::data('12+34');

        $data = new Data;

        $data->pattern('[0-9]+')->rules('data');

        $this->assertIsString($data->error('string')); 
    }
}