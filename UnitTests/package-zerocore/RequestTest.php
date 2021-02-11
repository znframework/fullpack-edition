<?php namespace ZN;

use Lang;

class RequestTest extends ZerocoreExtends
{
    public function testIsCurl()
    {
        $_SERVER['HTTP_COOKIE'] = true;

        $this->assertFalse(Request::isCurl());
    }

    public function testIsMethod()
    {
        $this->assertFalse(Request::isMethod('x'));
    }

    public function testIpv4()
    {
        $_SERVER['HTTP_CLIENT_IP'] = '127.0.0.0';

        $this->assertEquals('127.0.0.0', Request::ipv4());

        unset($_SERVER['HTTP_CLIENT_IP']);

        $_SERVER['HTTP_X_FORWARDED_FOR'] = '127.0.0.2';

        $this->assertEquals('127.0.0.2', Request::ipv4());

        unset($_SERVER['HTTP_X_FORWARDED_FOR']);

        $_SERVER['REMOTE_ADDR'] = '::1';

        $this->assertEquals('127.0.0.1', Request::ipv4());
    }

    public function testGetActiveURI()
    {
        Config::services('uri', ['lang' => true]);

        $_SERVER['REQUEST_URI'] = 'foo/bar/baz';

        $this->assertEquals('foo/bar/baz/', Request::getActiveURI(false));

        Config::services('uri', ['lang' => false]);
    }
}