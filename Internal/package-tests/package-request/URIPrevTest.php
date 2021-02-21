<?php namespace ZN\Request;

use URI;

class URIPrevTest extends \PHPUnit\Framework\TestCase
{
    public function testUriPrev()
    {
        $_SERVER['HTTP_REFERER'] = 'contact/us/sendForm';

        $this->assertEquals('contact/us/sendForm', URI::prev());
    }
    
    public function testUriPrevNoReferer()
    {
        $_SERVER['HTTP_REFERER'] = NULL;

        $this->assertEquals('', URI::prev());
    }

    public function testUriPrevFirstParameterSetFalse()
    {
        $_SERVER['HTTP_REFERER'] = 'contact/us/sendForm';

        $this->assertEquals('sendForm', URI::prev(false));
    }
}