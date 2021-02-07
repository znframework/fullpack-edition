<?php namespace ZN\Filesystem;

use Folder;

class FolderDiskTest extends FilesystemExtends
{
    public function testDisk()
    {
        Folder::create($directory = self::directory . 'disk');

        $this->assertIsFloat(Folder::disk($directory));
        $this->assertIsFloat(Folder::disk($directory, 'total'));

        Folder::delete($directory);
    }
    
    public function testdiskException()
    {
        try
        {
            Folder::disk(self::directory . 'unknown');
        }
        catch( Exception\FolderNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testTotalSpace()
    {
        $this->assertIsFloat(Folder::totalSpace(self::directory));
        $this->assertIsFloat(Folder::freeSpace(self::directory));
    }
}