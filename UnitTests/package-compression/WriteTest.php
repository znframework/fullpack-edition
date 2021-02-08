<?php namespace ZN\Compression;

use File;
use Compress;

class WriteTest extends CompressionExtends
{
    public function testWrite()
    {
        Compress::write(self::file, 'Example Data');

        $this->assertIsString(File::read(self::file));

        File::delete(self::file);
    }

    public function testBz()
    {
        Compress::driver('bz'); $this->testWrite();
    }

    public function testLzf()
    {
        try
        {
            Compress::driver('lzf'); $this->testWrite();
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testZlib()
    {
        Compress::driver('zlib'); $this->testWrite();
    }

    public function testRar()
    {
        try
        {
            Compress::driver('rar'); $this->testWrite();
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }   
    }

    public function testZip()
    {
        Compress::driver('zip'); $this->testWrite();
    }
}