<?php namespace ZN\Image;

use GD;

class FillTest extends Test\GDExtends
{
    public function testFill()
    {
        GD::canvas(300, 300, 'white')
          ->x(100)->y(100)->arc()
          ->x(200)->y(200)->arc()
          ->color('purple')->x(100)->y(100)->fill()
          ->color('blue')->x(200)->y(200)->fill()
          ->generate('png', $generateFile = self::dir . 'fill-300-300.png');

        $size = GD::size($generateFile);

        $this->assertSame([300, 300], [$size->width, $size->height]);
    }

    public function testFillWithBorderColor()
    {
        GD::canvas(600, 600, 'white')
          ->x(100)->y(100)->arc()
          ->x(200)->y(200)->arc()
          ->borderColor('200|40|200')
          ->color('purple')->x(100)->y(100)->fill()
          ->color('blue')->x(200)->y(200)->fill()
          ->generate('png', $generateFile = self::dir . 'fill-600-600.png');

        $size = GD::size($generateFile);

        $this->assertSame([600, 600], [$size->width, $size->height]);
    }
}