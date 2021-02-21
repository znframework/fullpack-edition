<?php namespace ZN\Filesystem;

use File;

class TruncateFileTest extends FilesystemExtends
{
    public function testTruncate()
    {
        File::write(self::file, 'example');
        
        File::truncate(self::file, 2);

        $this->assertSame('ex', File::read(self::file));

        File::delete(self::file);
    }

    public function testTruncateException()
    {
        try
        {
            File::truncate(self::file . 'unknown', 2);
        }
        catch( Exception\FileNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}