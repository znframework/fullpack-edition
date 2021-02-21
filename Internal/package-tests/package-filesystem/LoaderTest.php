<?php namespace ZN\Filesystem;

use File;
use Buffer;

class LoaderTest extends FilesystemExtends
{
    public function testException()
    {
        try
        {
            File::require(self::directory . 'test.csv2');
        }
        catch( Exception\FileNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testRequire()
    {
        Buffer::callback(function(){ File::require(self::directory . 'test.csv'); });
    }

    public function testRequireOnce()
    {
        Buffer::callback(function(){ File::requireOnce(self::directory . 'test.csv'); });
    }

    public function testInclude()
    {
        Buffer::callback(function(){ File::include(self::directory . 'test.csv'); });
    }

    public function testIncludeOnce()
    {
        Buffer::callback(function(){ File::includeOnce(self::directory . 'test.csv'); });
    }
}