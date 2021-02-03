<?php namespace ZN\Services;

use CDN;

class FontTest extends \PHPUnit\Framework\TestCase
{
    public function testFont()
    {
        $this->assertEmpty(CDN::font('abc'));
    }
}