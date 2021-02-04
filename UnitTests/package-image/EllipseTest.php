<?php namespace ZN\Image;

use GD;

class EllipseTest extends Test\GDExtends
{
    public function testEllipse()
    {
        GD::canvas(300, 300, 'white')
        ->x(100)->y(100)->width(100)->height(100)->color('red')->type('fill')->ellipse()
        ->generate('jpeg', $generateFile = self::dir . 'ellipse-300-300.jpg');

        $size = GD::size($generateFile);

        $this->assertSame([300, 300], [$size->width, $size->height]);
    }

    public function testEllipseNoStyle()
    {
        GD::canvas(600, 600, 'white')
        ->x(100)->y(100)->width(100)->height(100)->color('red')->type(NULL)->ellipse()
        ->generate('jpeg', $generateFile = self::dir . 'ellipse-600-600.jpg');

        $size = GD::size($generateFile);

        $this->assertSame([600, 600], [$size->width, $size->height]);
    }
}