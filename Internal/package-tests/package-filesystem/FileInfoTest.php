<?php namespace ZN\Filesystem;

use File;

class FileInfoTest extends FilesystemExtends
{
    public function testInfo()
    {
        File::write($file = self::file, 'test');

        $this->assertSame('test.txt', File::info($file)->basename);

        File::delete(self::file);
    }

    public function testInfoException()
    {
        try
        {
            File::info(self::file . 'unknown');
        }
        catch( Exception\FileNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testIsCall()
    {
        $this->assertTrue(File::readable(self::directory . 'test.csv'));
    }

    public function testGetRequiredFiles()
    {
        $this->assertIsArray(File::required());
    }

    public function testAccess()
    {
        File::access();
    }

    public function testExists()
    {
        $this->assertTrue(File::exists(self::directory . 'test.csv'));
        $this->assertFalse(File::exists(self::directory . 'test.csv2'));
    }

    public function testRelativePath()
    {
        $this->assertStringContainsString('test.csv', File::relativePath(self::directory . 'test.csv'));
    }

    public function testAvailable()
    {
        $this->assertTrue(File::available(self::directory . 'test.csv'));
        $this->assertFalse(File::available(self::directory . 'test.csv2'));
    }

    public function testOwner()
    {
        $this->assertIsArray((array) File::owner(self::directory . 'test.csv'));
    }

    public function testOwnerException()
    {
        try
        {
            File::owner(self::file . 'unknown');
        }
        catch( Exception\FileNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testGroup()
    {
        $this->assertIsArray((array) File::group(self::directory . 'test.csv'));
    }

    public function testGroupException()
    {
        try
        {
            File::group(self::file . 'unknown');
        }
        catch( Exception\FileNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}