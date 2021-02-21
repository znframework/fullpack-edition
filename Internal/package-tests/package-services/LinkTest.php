<?php namespace ZN\Services;

use CDN;
use File;

class LinkTest extends \ZN\Test\GlobalExtends
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
        $this->assertStringContainsString('keyframes.min.js', CDN::setJsonFile($jsonFile = self::default . 'package-services/example.json')
             ->links()['jquerykeyframes']);

        File::delete($jsonFile);
    }
}