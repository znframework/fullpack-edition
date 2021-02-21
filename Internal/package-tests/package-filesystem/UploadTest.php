<?php namespace ZN\Filesystem;

use Folder;
use Upload;
use ZN\Base;

class UploadTest extends FilesystemExtends
{
    public function testCreateFile()
    {
        $file = tempnam(sys_get_temp_dir(), 'Tux');

        $_FILES['file']['name']     = basename($file);
        $_FILES['file']['type']     = 'text/plain';
        $_FILES['file']['size']     = 1;
        $_FILES['file']['tmp_name'] = sys_get_temp_dir();
        $_FILES['file']['error']    = UPLOAD_ERR_OK;

        $_FILES['mfile']['name'][0]     = basename($file);
        $_FILES['mfile']['type'][0]     = 'text/plain';
        $_FILES['mfile']['size'][0]     = 1;
        $_FILES['mfile']['tmp_name'][0] = sys_get_temp_dir();
        $_FILES['mfile']['error'][0]    = UPLOAD_ERR_OK;
        $_FILES['mfile']['name'][1]     = basename($file);
        $_FILES['mfile']['type'][1]     = 'text/plain';
        $_FILES['mfile']['size'][1]     = 1;
        $_FILES['mfile']['tmp_name'][1] = sys_get_temp_dir();
        $_FILES['mfile']['error'][1]    = UPLOAD_ERR_OK;
    }

    public function testTargetSource()
    {
        Upload::target($directory = self::directory . 'uploads')->source('file')->start();

        $this->assertEmpty(Upload::error());

        Folder::delete($directory);
    }

    public function testMultiple()
    {
        # If invalid hash set md5
        Upload::encode('xyz')->target($directory = self::directory . 'uploads')->source('mfile')->start();

        $this->assertEmpty(Upload::error());
        $this->assertIsObject(Upload::info());

        Folder::delete($directory);
    }

    public function testMultipleError()
    {
        Upload::mimes('image/jpg')->target($directory = self::directory . 'uploads')->source('mfile')->start();

        $this->assertIsString(Upload::error());
        $this->assertIsObject(Upload::info());

        Folder::delete($directory);
    }

    public function testInvalidExtension()
    {
        Upload::extensions('png', 'jpg')->start('file', self::directory);

        $this->assertSame('Invalid file extension!', Upload::error());
    }

    public function testInvalidMime()
    {
        Upload::mimes('image/png')->start('file', self::directory);

        $this->assertSame('Invalid mime type!', Upload::error());
    }

    public function testConvertName()
    {
        Upload::convertName('my')->start('file', self::directory);

        $this->assertStringEndsWith('my.tmp', Base::suffix(Upload::info()->path, '.tmp'));
    }

    public function testEncode()
    {
        Upload::encode(false)->convertName('my')->start('file', self::directory);

        $this->assertSame('my.tmp', Base::suffix(basename(Upload::info()->path), '.tmp'));

    }

    public function testEncodeLength()
    {
        Upload::encode('md5')->encodeLength(4)->convertName('my')->start('file', self::directory);

        #xxxx-my.tmp = 11
        $this->assertSame(11, strlen(Base::suffix(basename(Upload::info()->path), '.tmp')));

    }

    public function testPrefix()
    {
        Upload::encode(false)->prefix('test.')->convertName('my')->start('file', self::directory);

        $this->assertSame('test.my.tmp', Base::suffix(basename(Upload::info()->path), '.tmp'));
    }

    public function testMaxsize()
    {
        Upload::maxsize(1000)->start('file', self::directory);

        $this->assertSame('Determine the maximum file size has been exceeded!', Upload::error());
    }

    public function testUploadIsFile()
    {
        $this->assertTrue(Upload::isFile('file'));
        $this->assertFalse(Upload::isFile('file2'));

        $file = tempnam(sys_get_temp_dir(), 'Tux');

        $_FILES['file'][0]['name']     = basename($file);
        $_FILES['file'][1]['name']     = basename($file);

        $this->assertTrue(Upload::isFile('file'));
    }

    public function testInfo()
    {
        $this->assertIsObject(Upload::info());
    }   

    public function testSettings()
    {
        Transfer::settings(['path' => self::directory]);
    }

    public function testUpload()
    {
        Transfer::upload('file');
    }
}