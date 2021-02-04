<?php namespace ZN\Image;

use GD;

class ScreenshotTest extends Test\GDExtends
{
    public function testScreenshot()
    {
        if( function_exists('imagegrabscreen') )
        {
            GD::screenshot()->generate('png', $generateFile = self::dir . 'screenshot.png');

            $this->assertFileExists($generateFile);
        } 
    }
}