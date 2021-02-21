<?php namespace ZN\Protection;

use Json;

class JsonCheckTest extends ProtectionExtends
{
    public function testCheckTrue()
    {
        $this->assertTrue(Json::check('{"foo":"Foo","bar":"Bar"}'));
    }

    public function testCheckFalse()
    {
        $this->assertFalse(Json::check('{"foo""Foo","bar":"Bar"}'));
    }
}