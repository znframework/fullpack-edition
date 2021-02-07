<?php namespace ZN\Filesystem;

use Document;

class ExtensionTest extends FilesystemExtends
{
    public function testGet()
    {
        $extension = new Extension;

        $this->assertEquals('csv', $extension->get(self::directory . 'test.csv'));
    }

    public function testRemove()
    {
        $extension = new Extension;

        $this->assertEquals(self::directory . 'test', $extension->remove(self::directory . 'test.csv'));
    }
}