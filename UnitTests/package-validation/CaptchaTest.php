<?php namespace ZN\Validation;

class CaptchaTest extends \ZN\Test\GlobalExtends
{
    public function testInvalid()
    {
        $this->assertFalse(Validator::captcha('zbc'));
    }  

    public function testRulesInvalid()
    {
        \Post::data('zbc');

        $data = new Data;

        $data->captcha()->rules('data');

        $this->assertIsString($data->error('string')); 
    }
}