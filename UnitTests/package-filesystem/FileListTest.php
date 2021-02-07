<?php namespace ZN\Filesystem;

use Document;

class FileListTest extends FilesystemExtends
{
    public function testFilesException()
    {
        try
        {
            $fileList = new FileList;

            $fileList->files(self::directory . 'unknown'); 
        }
        catch( Exception\FolderNotFoundException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}