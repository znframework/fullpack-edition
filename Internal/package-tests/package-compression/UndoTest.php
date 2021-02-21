<?php namespace ZN\Compression;

class UndoTest extends CompressionExtends
{
    public function testGz()
    {
        $this->undo('gz');
    }

    public function testBz()
    {
        $this->undo('bz');
    }

    public function testLzf()
    {
        $this->undo('lzf');
    }

    public function testZlib()
    {
        $this->undo('zlib');
    }

    public function testRar()
    {
        $this->undo('rar');  
    }

    public function testZip()
    {
        $this->undo('zip');
    }
}