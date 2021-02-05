<?php namespace ZN\Validation;

class AlphaTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::alpha('abc'));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::alpha('ab14'));
    }

    public function testRulesValid()
    {
        \Post::data('abc');

        $data = new Data;

        $data->alpha()->rules('data');

        $this->assertEmpty($data->error('string')); 
    }
}