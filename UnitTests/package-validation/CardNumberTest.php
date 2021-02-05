<?php namespace ZN\Validation;

class CardNumberTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::card('5555555555554444', 'maestro'));
    }  
    
    public function testInvalid()
    {
        $this->assertFalse(Validator::card('5555555555554444', 'visa'));
    }  

    public function testTypeDetected()
    {
        $this->assertTrue(Validator::card('5555555555554444'));
    } 

    public function testRulesValid()
    {
        \Post::data('5555555555554444');

        $data = new Data;

        $data->card('maestro')->rules('data');

        $this->assertEmpty($data->error('string')); 
    }

    public function testRulesInvalid()
    {
        \Post::data('5555555555554444');

        $data = new Data;

        $data->card('visa')->rules('data');

        $this->assertIsString($data->error('string')); 
    }
}