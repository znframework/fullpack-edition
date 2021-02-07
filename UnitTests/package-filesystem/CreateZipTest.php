<?php namespace ZN\Filesystem;

use File;
use Folder;

class CreateZipTest extends FilesystemExtends
{
    public function testCreateZip()
    {
        File::create($file1 = self::directory . '1.txt');
        File::create($file2 = self::directory . '2.txt');
        
        # If directory not exists createing.
        # file and directory
        # alias file name
        File::createZip($zipFile = self::directory . 'zip/example.zip', [$file1, $file2 => 'file name', self::directory]);

        # Delete Before
        File::createZip($zipFile, [$file1, $file2]);

        $this->assertFileExists($zipFile);

        File::delete($file1);
        File::delete($file2);
        Folder::delete(self::directory . 'zip');
    }
}