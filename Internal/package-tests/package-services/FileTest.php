<?php namespace ZN\Services;

use CDN;

class FileTest extends \PHPUnit\Framework\TestCase
{
    public function testFile()
    {
        $this->assertEmpty(CDN::file('abc'));
    }
}