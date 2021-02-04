<?php namespace ZN\Image;

use GD;

class CropTest extends Test\GDExtends
{
    public function testCrop()
    {
        GD::canvas(self::img)
          ->x(100)->y(100)->width(100)->height(300)->crop()
          ->generate('png', $generateFile = self::dir . 'image-crop.png');

        $this->assertFileExists($generateFile);
    }

    public function testCropInvalidArgumentException()
    {   
        try
        {
            GD::canvas(self::img)
            ->x(100)->y(100)->width(100)->height(300)->crop()
            ->generate('png', $generateFile = self::dir . 'image-crop.png2');
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertStringContainsString($generateFile, $e->getMessage());
        }
    }
}