<?php namespace ZN;

use Folder;

class FilesystemTest extends ZerocoreExtends
{
    public function testZipExtract()
    {
        $this->assertTrue(Filesystem::zipExtract($dir = self::resources . 'example'));

        Folder::delete($dir);

        $this->assertFalse(Filesystem::zipExtract(self::resources . 'unknown'));

        $this->assertFalse(Filesystem::zipExtract(self::resources . 'invalid'));
    }

    public function testCreateFolder()
    {
        $this->assertFalse(Filesystem::createFolder(self::resources));
    }

    public function testDeleteFolder()
    {
        $this->assertFalse(Filesystem::deleteFolder(self::resources . 'invalid'));
    }

    public function testDeleteEmptyFolder()
    {
        $this->assertFalse(Filesystem::deleteEmptyFolder(self::resources . 'invalid'));
    }

    public function testCopy()
    {
        try
        {
            Filesystem::copy(self::resources . 'invalid', self::resources . 'target');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }  
    }

    public function testGetRecursiveFiles()
    {
        $this->assertIsArray(Filesystem::getRecursiveFiles('/'));
    }
}