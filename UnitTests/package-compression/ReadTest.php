<?php namespace ZN\Compression;

class ReadTest extends CompressionExtends
{
    public function testGz()
    {
        $this->read('gz');
    }

    public function testBz()
    {
        $this->read('bz');
    }

    public function testLzf()
    {
        $this->read('lzf');
    }

    public function testZlib()
    {
        $this->read('zlib');
    }

    public function testRar()
    {
        $this->read('rar');
    }

    public function testZip()
    {
        $this->read('zip');
    }
}