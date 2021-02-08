<?php namespace ZN\Compression;

use File;
use Compress;

class ReadTest extends CompressionExtends
{
    public function testRead()
    {
        Compress::write(self::file, 'Example Data');

        $this->assertEquals('Example Data', Compress::read(self::file));

        File::delete(self::file);
    }

    public function testBz()
    {
        Compress::driver('bz'); $this->testRead();
    }

    public function testLzf()
    {
        try
        {
            Compress::driver('lzf'); $this->testRead();
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testZlib()
    {
        Compress::driver('zlib'); $this->testRead();
    }

    public function testRar()
    {
        try
        {
            Compress::driver('rar'); $this->testRead();
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }   
    }

    public function testZip()
    {
        Compress::driver('zip'); $this->testRead();
    }
}