<?php namespace ZN\Filesystem;

use Buffer;

class TransferTest extends FilesystemExtends
{
    public function testDownload()
    {
        Buffer::callback(function()
        {
            Transfer::download(self::directory . 'test.csv');
        });
    }
}