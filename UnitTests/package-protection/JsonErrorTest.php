<?php namespace ZN\Protection;

use Json;

class JsonErrorTest extends ProtectionExtends
{
    public function testError()
    {
        Json::check('{"foo""Foo","bar":"Bar"}');

        $this->assertEquals('Syntax error', Json::error());
    }

    public function testErrno()
    {
        Json::check('{"foo""Foo","bar":"Bar"}');

        $this->assertEquals(4, Json::errno());
    }
}