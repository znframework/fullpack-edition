<?php namespace ZN\Filesystem;

use Folder;

class DeleteFolderTest extends FilesystemExtends
{
    public function testDelete()
    {
        Folder::create(self::dir);

        $this->assertTrue(Folder::delete(self::dir));
    }

    public function testDeleteEmpty()
    {
        Folder::create($directory = self::directory . 'example');

        $this->assertTrue(Folder::deleteEmpty($directory));
    }
}