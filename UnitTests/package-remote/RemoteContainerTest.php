<?php namespace ZN\Remote;

class RemoteContainerTest extends RemoteExtendz
{
    public function testContainer()
    {
        try
        {
            $ftp = new Remote(new FTP);
        }
        catch( Exception\IOException $e )
        {
            $this->assertStringContainsString('`Connect`', $e->getMessage());
        }  
    }
}