<?php namespace ZN\Remote;

class FTPCreateFolderTest extends RemoteExtendz
{
    public function testCreateFolder()
    {
        $this->ftp->expects($this->once())
                  ->method('createFolder')
                  ->with('bar')
                  ->willReturn(true);

        $this->assertTrue($this->ftp->createFolder('bar'));
    }

    public function testCreateFolderFolderAllreadyException()
    {
        $this->ftp->expects($this->once())
                  ->method('createFolder')
                  ->willThrowException(new Exception\FolderAllreadyException(NULL, 'bar'));

        try
        {
            $this->ftp->createFolder('bar');
        }
        catch( Exception\FolderAllreadyException $e )
        {
            $this->assertStringContainsString('`bar`', $e->getMessage());
        }
    }
}