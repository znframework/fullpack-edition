<?php namespace ZN\Protection;

use Json;

class JsonReadTest extends ProtectionExtends
{
    public function testRead()
    {
        $this->assertEquals((object) ['foo' => 'Foo', 'bar' => 'Bar'], Json::read(self::dir . 'example'));
    }

    public function testReadObject()
    {
        $this->assertEquals((object) ['foo' => 'Foo', 'bar' => 'Bar'], Json::readObject(self::dir . 'example'));
    }

    public function testReadArray()
    {
        $this->assertEquals(['foo' => 'Foo', 'bar' => 'Bar'], Json::readArray(self::dir . 'example'));
    }

    public function testReadReturnFalse()
    {
        $this->assertFalse(Json::read(self::dir . 'unknown'));
    }
}