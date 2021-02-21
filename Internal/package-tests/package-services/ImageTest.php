<?php namespace ZN\Services;

use CDN;

class ImageTest extends \PHPUnit\Framework\TestCase
{
    public function testImage()
    {
        $this->assertEmpty(CDN::image('abc'));
    }
}