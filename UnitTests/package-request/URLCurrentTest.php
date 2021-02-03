<?php namespace ZN\Request;

use URL;

class URLCurrentTest extends \PHPUnit\Framework\TestCase
{
    public function testUrlCurrent()
    {
        $_SERVER['REQUEST_URI'] = 'contact/us/sendForm';

        # Does not include BASE_DIR
        $this->assertStringEndsWith('contact/us/sendForm', URL::current());
    }
    

    public function testUrlCurrentFirstParameter()
    {
        $this->assertStringContainsString('/about/me', URL::current('about/me'));
    }

    public function testUrlCurrentFirstCharSlash()
    {
        $_SERVER['REQUEST_URI'] = '/contact/us/sendForm';

        $this->assertStringContainsString('/about/me', URL::current('about/me'));
    }

    public function testUrlCurrentSetFalseFirstParameter()
    {
        $_SERVER['REQUEST_URI'] = '/contact/us/sendForm';

        $this->assertStringContainsString('contact/us/sendForm', URL::current(false));
    }
}