<?php namespace ZN\Image;

use GD;

class ScreenshotTest extends Test\GDExtends
{
    public function testScreenshot()
    {
        GD::screenshot()->generate('png', $generateFile = self::dir . 'screenshot.png');

        $this->assertFileExists($generateFile);
    }
}