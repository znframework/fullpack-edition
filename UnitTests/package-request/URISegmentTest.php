<?php namespace ZN\Request;

use URI;

class URISegmentTest extends \PHPUnit\Framework\TestCase
{
    public function testUriSegment()
    {
        $_SERVER['REQUEST_URI'] = 'contact/us/sendForm/count/test';

        $this->assertEquals('contact', URI::segment(1));
        $this->assertEquals('us', URI::segment(2));
    }

    public function testUriSegmentNegative()
    {
        $_SERVER['REQUEST_URI'] = 'contact/us/sendForm/count/test';

        $this->assertEquals('test', URI::segment(-1));
        $this->assertEquals('count', URI::segment(-2));
    }

    public function testUriSegmentCallStart()
    {
        $_SERVER['REQUEST_URI'] = 'contact/us/sendForm/count/test';

        $this->assertEquals('contact', URI::s1());
        $this->assertEquals('us', URI::s2());
    }

    public function testUriSegmentCallEnd()
    {
        $_SERVER['REQUEST_URI'] = 'contact/us/sendForm/count/test';

        $this->assertEquals('test', URI::e1());
        $this->assertEquals('count', URI::e2());
    }

    public function testUriSegmentZero()
    {
        $_SERVER['REQUEST_URI'] = 'contact/0/sendForm/count/test';

        $this->assertEquals(0, URI::segment(2));
    }

    public function testUriSegmentNone()
    {
        $_SERVER['REQUEST_URI'] = 'contact/0/sendForm/count/test';

        $this->assertEquals('', URI::segment(10));
    }

    public function testUriSegmentCount()
    {
        $_SERVER['REQUEST_URI'] = 'contact/us/sendForm/count/test';

        $this->assertEquals(5, URI::segmentCount());
    }
}