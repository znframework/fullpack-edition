<?php namespace ZN\Filesystem;

use Buffer;
use Document;

class DownloadTest extends FilesystemExtends
{
    public function testMake()
    {
        Buffer::callback(function()
        {
            $download = new Download;

            $download->start(self::directory . 'test.csv');
        });
       
    }

    public function testMakeException()
    {
        try
        {
            $download = new Download;

            $download->start(self::directory . 'test.csvx');
        }
        catch( Exception\FileNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}