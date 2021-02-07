<?php namespace ZN\Filesystem;

use File;

class CleanCacheTest extends FilesystemExtends
{
    public function testMake()
    {
        File::create(self::file);

        File::cleanCache(self::file);

        $this->assertFileExists(self::file);

        File::delete(self::file);
    }

    public function testReal()
    {
        File::cleanCache(self::file . 'unknown');

    }
}