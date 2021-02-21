<?php namespace ZN\Image;

use GD;

class PixelTest extends Test\GDExtends
{
    public function testPixel()
    {
        GD::canvas(400, 400, 'white')
          ->x(10)->y(10)->color('red')->pixel()
          ->x(10)->y(11)->color('blue')->pixel()
          ->x(10)->y(12)->color('200|20|30')->pixel()
          ->x(10)->y(13)->color('green')->pixel()
          ->x(10)->y(14)->color('pink')->pixel()
          ->generate('png', $generateFile = self::dir . 'image-line.png');

        $this->assertFileExists($generateFile);
    }
}