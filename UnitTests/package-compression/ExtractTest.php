<?php namespace ZN\Compression;

class ExtractTest extends CompressionExtends
{
    public function testGz()
    {
        $this->extract('gz');
    }

    public function testBz()
    {
        $this->extract('bz');
    }

    public function testLzf()
    {
        $this->extract('lzf');
    }

    public function testZlib()
    {
        $this->extract('zlib');
    }

    public function testRar()
    {
        $this->extract('rar');  
    }

    public function testZip()
    {
        $this->extract('zip');
    }
}