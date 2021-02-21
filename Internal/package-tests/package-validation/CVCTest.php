<?php namespace ZN\Validation;

class CVCTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::cvc('978', 'maestro'));
    }  
    
    public function testInvalid()
    {
        $this->assertFalse(Validator::cvc('4978', 'visa'));
    }  

    public function testRulesValid()
    {
        \Post::data('978');

        $data = new Data;

        $data->cvc('maestro')->rules('data');

        $this->assertEmpty($data->error('string')); 
    }

    public function testRulesInvalid()
    {
        \Post::data('4978');

        $data = new Data;

        $data->cvc('visa')->rules('data');

        $this->assertIsString($data->error('string')); 
    }
}