<?php namespace ZN\Filesystem;

use Folder;

class ChangeFolderTest extends FilesystemExtends
{
    public function testChange()
    {
        Folder::change('/');
    }

    public function testChangeException()
    {
        try
        {
            Folder::change('/unhknown');
        }
        catch( Exception\FolderNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}