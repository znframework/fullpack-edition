<?php namespace ZN\Remote;

class FTPConnectionTest extends RemoteExtendz
{
    public function testSSLConnection()
    {
        try
        {
            $ftp = new FTP(['ssl' => true]);
        }
        catch( Exception\IOException $e )
        {
            $this->assertStringContainsString('`Connect`', $e->getMessage());
        }  
    }

    public function testConnectionError()
    {
        try
        {
            $ftp = new FTP;
        }
        catch( Exception\IOException $e )
        {
            $this->assertStringContainsString('`Connect`', $e->getMessage());
        }
        
    }
}