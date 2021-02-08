<?php namespace ZN\Compression;

class WriteTest extends CompressionExtends
{
    public function testWrite()
    {
        $this->write('gz');
    }

    public function testBz()
    {
        $this->write('bz');
    }

    public function testLzf()
    {
        $this->write('lzf');
    }

    public function testZlib()
    {
        $this->write('zlib');
    }

    public function testRar()
    {
        $this->write('rar');
    }

    public function testZip()
    {
        $this->write('zip');
    }
}