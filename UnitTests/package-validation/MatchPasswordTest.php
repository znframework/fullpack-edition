<?php namespace ZN\Validation;

class MatchPasswordTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::matchPassword('1234', '1234'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::matchPassword('1234', '12345'));
    }  

    public function testRulesValid()
    {
        \Post::data('1234');
        \Post::match('1234');

        $data = new Data;

        $data->matchPassword('match')->rules('data');

        $this->assertEmpty($data->error('string')); 
    }

    public function testRulesInvalid()
    {
        \Post::data('1234');
        \Post::match('12345');

        $data = new Data;

        $data->matchPassword('match')->rules('data');

        $this->assertIsString($data->error('string')); 
    }
}