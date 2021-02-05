<?php namespace ZN\Validation;

class MatchTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::match('1234', '1234'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::match('1234', '12345'));
    }  

    public function testRulesValid()
    {
        \Post::data('1234');
        \Post::match('1234');

        $data = new Data;

        $data->match('match')->rules('data');

        $this->assertEmpty($data->error('string')); 
    }

    public function testRulesInvalid()
    {
        \Post::data('1234');
        \Post::match('12345');

        $data = new Data;

        $data->match('match')->rules('data');

        $this->assertIsString($data->error('string')); 
    }
}