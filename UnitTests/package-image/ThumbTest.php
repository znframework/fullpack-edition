<?php namespace ZN\Image;

use Thumb;
use Folder;

class ThumbTest extends \ZN\Test\GlobalExtends
{
    const dir = self::default . 'package-image/resources/';
    const img = self::dir . 'image.jpg';

    public function testCreate()
    {
        $this->assertStringContainsString
        (
            'resources/thumbs/image-100x200px-730x501size.jpg', 
            Thumb::path(self::img)->crop(100, 200)->create()
        );
    }

    public function testCreateImageNotFoundException()
    {
        try
        {
            Thumb::path($path = self::img . 'xbyz')->crop(100, 200)->create();
        }
        catch( Exception\ImageNotFoundException $e )
        {
            $this->assertStringContainsString($path, $e->getMessage());
        }
    }

    public function testCreateReturnEmpty()
    {
        $this->assertEmpty(Thumb::path('zeroneed.php')->crop(100, 200)->create());
    }

    public function testCreateWithPNG()
    {
        $this->assertStringContainsString
        (
            'resources/thumbs/image-0x0px-100x100size.png', 
            Thumb::quality(100)->path(self::dir . 'image.png')->resize(100, 100)->create()
        );
    }

    public function testCropAndSize()
    {
        $this->assertStringContainsString
        (
            'resources/thumbs/image-600x300px-300x300size.jpg', 
            Thumb::path(self::img)->crop(600, 300)->size(300, 300)->create()
        );
    }

    public function testResize()
    {
        $this->assertStringContainsString
        (
            'resources/thumbs/image-0x0px-200x400size.jpg', 
            Thumb::path(self::img)->resize(200, 400)->create()
        );
    }

    public function testProsize()
    {
        $this->assertStringContainsString
        (
            'resources/thumbs/image-100x200px-200x137size.jpg', 
            Thumb::path(self::img)->crop(100, 200)->prosize(200)->create()
        );
    }

    public function testGetProsize()
    {
        $this->assertIsObject(Thumb::path(self::img)->getProsize(200));
    }

    public function testGetProsizeSizeGreaterThanHeight()
    {
        $this->assertIsObject(Thumb::path(self::img)->getProsize(10, 10));
    }

    public function testWatermark()
    {
        $this->assertStringContainsString
        (
            'resources/thumbs/image-150x275px-730x501size.jpg', 
            Thumb::path(self::img)->crop(150, 275)->watermark(self::dir . 'image-flip.png', 'center')->create()
        );
    }

    public function testBackground()
    {
        $this->assertStringContainsString
        (
            'resources/thumbs/image-0x0px-146x100size.jpg', 
            Thumb::path(self::img)->prosize(0, 100)->background(400, 300, 'red')->create()
        );
    }

    public function testClean()
    {
        Thumb::clean(self::img);

        $this->assertSame
        (
            0, 
            count(Folder::files(self::dir . 'thumbs'))
        );
    }

    public function testCleanItSelf()
    {
        Thumb::clean(self::dir . 'thumbs/image-0x0px-146x100size.jpg', true);
    }

    public function testCreateApplyFilter()
    {
        $this->assertStringContainsString
        (
            'resources/thumbs/image-350x200px-730x501size.jpg', 
            Thumb::path(self::img)->colorize(80, 50, 60)->crop(350, 200)->create()
        );
    }
}