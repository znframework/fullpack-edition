<?php namespace ZN\Services;

use CDN;
use File;

class LinkTest extends \PHPUnit\Framework\TestCase
{
    public function testLink()
    {
        $this->assertStringContainsString('keyframes.min.js', CDN::link('jquerykeyframes'));
    }

    public function testLinks()
    {
        $this->assertStringContainsString('keyframes.min.js', CDN::links()['jquerykeyframes']);
    }

    public function testLinkByVersion()
    {
        $this->assertStringContainsString('keyframes.min.js', CDN::link('jquerykeyframes', '3'));
    }

    public function testRefresh()
    {
        $this->assertStringContainsString('keyframes.min.js', CDN::refresh()->links()['jquerykeyframes']);
    }

    public function testSetJsonFile()
    {
        $this->assertStringContainsString('keyframes.min.js', CDN::setJsonFile($jsonFile = 'UnitTests/package-services/example.json')
             ->links()['jquerykeyframes']);

        File::delete($jsonFile);
    }
}