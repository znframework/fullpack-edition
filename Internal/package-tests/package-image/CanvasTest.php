<?php namespace ZN\Image;

use GD;
use Buffer;

class CanvasTest extends Test\GDExtends
{
    public function testCanvasReal()
    {
        $file = Buffer::callback(function()
        {
            GD::canvas(300, 300, 'white', true)->generate('png');
        });
       
        $this->assertIsString($file);
    }
}