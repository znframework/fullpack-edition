<?php namespace ZN\Validation;

class CardDateTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::cardDate('10/30'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::cardDate('1030'));
    }

    public function testRulesValid()
    {
        \Post::data('10/30');

        $data = new Data;

        $data->cardDate()->rules('data');

        $this->assertEmpty($data->error('string')); 
    }

    public function testRulesInvalid()
    {
        \Post::data('1030');

        $data = new Data;

        $data->cardDate()->rules('data');

        $this->assertIsString($data->error('string')); 
    }
}