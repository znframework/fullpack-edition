<?php namespace ZN\Validation;

class NumericTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::numeric('1234'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::numeric('ab14'));
    }

    public function testRulesValid()
    {
        \Post::data('1030');

        $data = new Data;

        $data->numeric()->rules('data');

        $this->assertEmpty($data->error('string')); 
    }
}