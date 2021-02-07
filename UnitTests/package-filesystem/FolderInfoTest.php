<?php namespace ZN\Filesystem;

use Folder;

class FolderInfoTest extends FilesystemExtends
{
    public function testGetFileInfoDirectory()
    {
        $this->assertIsArray(Folder::fileInfo(self::directory));
    }

    public function testGetFileInfo()
    {
        $this->assertIsArray(Folder::fileInfo(self::directory . 'test.csv'));
    }

    public function testGetFileInfoException()
    {
        try
        {
            Folder::fileInfo(self::directory . 'test.csv2');
        }
        catch( Exception\FolderNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}