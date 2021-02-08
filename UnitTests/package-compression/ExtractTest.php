<?php namespace ZN\Compression;

use Folder;
use Compress;

class ExtractTest extends CompressionExtends
{
    const path = self::default . 'package-compression/resources/';

    public function testGz()
    {
        try
        {
            Compress::extract(self::path . 'example.zip', self::path . 'example');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testBz()
    {
        try
        {
            Compress::driver('bz')->extract(self::path . 'example.zip', self::path . 'example');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testLzf()
    {
        try
        {
            Compress::driver('lzf')->extract(self::path . 'example.zip', self::path . 'example');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testZlib()
    {
        try
        {
            Compress::driver('zlib')->extract(self::path . 'example.zip', self::path . 'example');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testRar()
    {
        try
        {
            Compress::driver('rar')->extract(self::path . 'example.rar', $target = self::path . 'rar');

            $this->assertDirectoryExists($target);;
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }   
    }

    public function testZip()
    {
        Compress::driver('zip')->extract(self::path . 'example.zip', $target = self::path . 'zip');
 
        $this->assertDirectoryExists($target);

        Folder::delete($target);
    }
}