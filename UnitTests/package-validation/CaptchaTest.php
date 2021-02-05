<?php namespace ZN\Validation;

class CaptchaTest extends \ZN\Test\GlobalExtends
{
    public function testInvalid()
    {
        $this->assertFalse(Validator::captcha('zbc'));
    }  
}