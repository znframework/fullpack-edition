<?php namespace ZN\Filesystem;

use File;

class FileSizeTest extends FilesystemExtends
{
    public function testSize()
    {
        File::write(self::file, 'test');

        $this->assertSame(4, (int) File::size(self::file));

        File::delete(self::file);
    }

    public function testFolderSize()
    {
        $this->assertIsFloat(File::size(self::directory));
        $this->assertIsFloat(File::size(self::directory, 'kb'));
        $this->assertIsFloat(File::size(self::directory, 'mb'));
        $this->assertIsFloat(File::size(self::directory, 'gb'));
    }

    public function testInfoException()
    {
        try
        {
            File::size(self::file . 'unknown');
        }
        catch( Exception\FileNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}