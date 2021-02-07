<?php namespace ZN\Filesystem;

use File;

class ReglaceFileTest extends FilesystemExtends
{
    public function testReglace()
    {
        File::write(self::file, 'test');

        File::reglace(self::file, '{word}', 'example');

        $this->assertSame('example', File::read(self::file));

        File::delete(self::file);
    }

    public function testReglaceFalse()
    {
        try
        {
            File::reglace(self::file . 'unknown', 'test', 'example');
        }
        catch( Exception\FileNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}