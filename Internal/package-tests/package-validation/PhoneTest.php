<?php namespace ZN\Validation;

class PhoneTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::phone('12+34', '**+**'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::phone('1234', '**-**'));
    }

    public function testRulesValid()
    {
        \Post::data('12+34');

        $data = new Data;

        $data->phone('**+**')->rules('data');

        $this->assertEmpty($data->error('string')); 
    }

    public function testRulesInvalid()
    {
        \Post::data('12+34');

        $data = new Data;

        $data->phone('*****')->rules('data');

        $this->assertIsString($data->error('string')); 
    }

    public function testRulesEmpty()
    {
        \Post::data('12+34');

        $data = new Data;

        $data->phone()->rules('data');

        $this->assertIsString($data->error('string')); 
    }
}