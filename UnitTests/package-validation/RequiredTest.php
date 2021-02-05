<?php namespace ZN\Validation;

class RequiredTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::required(0));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::required(''));
    }  

    public function testRulesValid()
    {
        \Post::data('10/30');

        $data = new Data;

        $data->required()->rules('data');

        $this->assertEmpty($data->error('string')); 
    }
}