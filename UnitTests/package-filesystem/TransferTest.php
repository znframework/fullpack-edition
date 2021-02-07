<?php namespace ZN\Filesystem;

use Buffer;

class TransferTest extends FilesystemExtends
{
    public function testSettings()
    {
        Transfer::settings(['path' => self::directory]);
    }

    public function testUpload()
    {
        Transfer::upload('file');
    }

    public function testDownload()
    {
        Buffer::callback(function()
        {
            Transfer::download(self::directory . 'test.csv');
        });
    }
}