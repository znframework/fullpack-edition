<?php namespace ZN\Compression;

use Compress;

class DoTest extends CompressionExtends
{
    public function testDo()
    {
        $compress = Compress::do('Example Data');

        $this->assertIsString($compress);
    }

    public function testBz()
    {
        Compress::driver('bz'); $this->testDo();
    }

    public function testLzf()
    {
        try
        {
            Compress::driver('lzf'); $this->testDo();
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testZlib()
    {
        Compress::driver('zlib'); $this->testDo();
    }

    public function testRar()
    {
        try
        {
            Compress::driver('rar'); $this->testDo();
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }   
    }

    public function testZip()
    {
        Compress::driver('zip'); $this->testDo();
    }
}