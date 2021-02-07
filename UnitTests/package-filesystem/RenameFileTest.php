<?php namespace ZN\Filesystem;

use File;

class RenameFileTest extends FilesystemExtends
{
    public function testRename()
    {
        File::create(self::file);

        File::rename(self::file, $file = self::directory . 'rename-file.txt');

        $this->assertFileExists($file);

        File::delete($file);
    }

    public function testRenameException()
    {
        try
        {
            File::rename(self::file . 'unknown', $file = self::directory . 'rename-file.txt');
        }
        catch( Exception\FileNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}