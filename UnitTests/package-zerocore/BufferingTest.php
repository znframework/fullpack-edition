<?php namespace ZN;

class BufferingTest extends ZerocoreExtends
{
    public function testStart()
    {
        define('HTACCESS_CONFIG', ['cache' => ['obGzhandler' => true]]); 

        $_SERVER['HTTP_ACCEPT_ENCODING'] = false;

        Buffering::start();
    
        Buffering::end();
    }   

    public function testStartGz()
    {
        $_SERVER['HTTP_ACCEPT_ENCODING'] = 'gzip';

        Buffering::start();
    
        Buffering::end();
    }  
    
    public function testFile()
    {
        try
        {
            Buffering::file('unknownfile');
        }
        catch( \Exception $e )
        {
            $this->assertEquals('`1.($file)` parameter should contain the file data type!', $e->getMessage());
        }
    }  
}