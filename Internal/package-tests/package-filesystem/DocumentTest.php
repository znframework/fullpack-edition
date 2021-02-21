<?php namespace ZN\Filesystem;

use Document;

class DocumentTest extends FilesystemExtends
{
    public function testFile()
    {
        $this->assertSame
        (
            'Hello Body!',
            Document::target(self::directory . 'example.txt')
                    ->create()
                    ->write('Hello Body!')
                    ->read()
                    ->apply()
        );
    }

    public function testFolder()
    {
        $this->assertTrue
        (
            Document::target(self::directory . 'abc/')
                    ->create()
                    ->delete()
                    ->apply()
        );
    }
}