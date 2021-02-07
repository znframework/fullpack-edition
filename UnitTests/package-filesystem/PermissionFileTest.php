<?php namespace ZN\Filesystem;

use File;

class PermissionFileTest extends FilesystemExtends
{
    public function testPermission()
    {
        File::create(self::file);

        File::permission(self::file, 644);

        $this->assertFalse(File::info(self::file)->executable);

        File::delete(self::file);
    }

    public function testPermissionException()
    {
        try
        {
            File::permission(self::file . 'unknown', 644);
        }
        catch( Exception\FileNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}