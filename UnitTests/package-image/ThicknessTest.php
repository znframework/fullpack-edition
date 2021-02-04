<?php namespace ZN\Image;

use GD;

class ThicknessTest extends Test\GDExtends
{
    public function testThickness()
    {
      GD::canvas(400, 400, 'white')
          ->thickness(10)->x1(100)->y1(100)->x2(200)->y2(200)->color('red')->line()
          ->thickness(20)->x1(100)->y1(100)->x2(0)->y2(0)->color('blue')->line()
          ->generate('png', $generateFile = self::dir . 'image-thickness.png');

        $this->assertFileExists($generateFile);
    }
}