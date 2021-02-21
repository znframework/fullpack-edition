<?php namespace ZN\Compression;

class DoTest extends CompressionExtends
{
    public function testGz()
    {
        $this->do('gz');
    }

    public function testBz()
    {
        $this->do('bz');
    }

    public function testLzf()
    {
        $this->do('lzf');
    }

    public function testZlib()
    {
        $this->do('zlib');
    }

    public function testRar()
    {
        $this->do('rar'); 
    }

    public function testZip()
    {
        $this->do('zip');
    }
}