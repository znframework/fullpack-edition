<?php namespace ZN\Image;

use GD;

class RotateTest extends Test\GDExtends
{
    public function testRotate()
    {
        GD::canvas(self::img)
          ->rotate(90)
          ->generate('png', $generateFile = self::dir . 'image-rotate.png');

        $this->assertFileExists($generateFile);
    }

    public function testRotateTransparent()
    {
        GD::canvas(self::img)
          ->rotate(90, 'transparent')
          ->generate('png', $generateFile = self::dir . 'image-rotate.png');

        $this->assertFileExists($generateFile);
    }
}