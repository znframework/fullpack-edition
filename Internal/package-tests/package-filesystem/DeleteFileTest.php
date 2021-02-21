<?php namespace ZN\Filesystem;

use File;

class DeleteFileTest extends FilesystemExtends
{
    public function testDelete()
    {
        File::write(self::file, 'test');
        
        File::delete(self::file);

        $this->assertFalse(is_file(self::file));

        File::write(self::file, 'test');
    }

    public function testDeleteException()
    {
        try
        {
            File::delete(self::file . 'unknown');
        }
        catch( Exception\FileNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}