<?php namespace ZN\Services;

use CDN;

class CDNGetTest extends \PHPUnit\Framework\TestCase
{
    public function testGet()
    {
        $this->assertStringContainsString('bootstrap.min.js', CDN::get('scripts', 'bootstrap'));
    }

    public function testGetReturnEmpty()
    {
        $this->assertEmpty(CDN::get('unknown', 'bootstrap'));
    }
}