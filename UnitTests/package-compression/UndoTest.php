<?php namespace ZN\Compression;

use Compress;

class UndoTest extends CompressionExtends
{
    public function testUndo()
    {
        $compress = Compress::do('Example Data');

        $undo = Compress::undo($compress);

        $this->assertIsString('Example Data', $undo);
    }

    public function testBz()
    {
        Compress::driver('bz'); $this->testUndo();
    }

    public function testLzf()
    {
        try
        {
            Compress::driver('lzf'); $this->testUndo();
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testZlib()
    {
        Compress::driver('zlib'); $this->testUndo();
    }

    public function testRar()
    {
        try
        {
            Compress::driver('rar'); $this->testUndo();
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }   
    }

    public function testZip()
    {
        Compress::driver('zip'); $this->testUndo();
    }
}