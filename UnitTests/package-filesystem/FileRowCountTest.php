<?php namespace ZN\Filesystem;

use File;

class FileRowCountTest extends FilesystemExtends
{
    public function testRowCount()
    {
        File::write(self::file, "test\nexample");

        $this->assertSame(2, File::rowCount(self::file));

        File::delete(self::file);
    }

    public function testFolderRowCount()
    {
        $this->assertIsInt(File::rowCount(self::directory));
    }
    
    public function testGroupException()
    {
        try
        {
            File::rowCount(self::file . 'unknown');
        }
        catch( Exception\FileNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}