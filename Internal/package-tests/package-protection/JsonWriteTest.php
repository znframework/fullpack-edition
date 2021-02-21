<?php namespace ZN\Protection;

use Json;

class JsonWriteTest extends ProtectionExtends
{
    public function testWrite()
    {
        Json::write(self::dir . 'example', ['foo' => 'Foo', 'bar' => 'Bar']);

        $this->assertFileExists(self::dir . 'example.json');
    }

    public function testWriteScalarDataException()
    {
        try
        {
            Json::write(self::dir . 'example', 'example data');
        }
        catch( Exception\ScalarDataException $e )
        {
            $this->assertStringContainsString('[data]', $e->getMessage());
        }
    }
}