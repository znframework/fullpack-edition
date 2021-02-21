<?php namespace ZN\Image;

use GD;
use Buffer;

class GetImageContentTest extends Test\GDExtends
{
    public function testQuality()
    {
        Buffer::callback(function()
        {
            GD::canvas(self::img)
            ->quality(1)
            ->output(true)
            ->generate('png', false);
        });
    }
}