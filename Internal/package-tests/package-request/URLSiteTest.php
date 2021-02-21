<?php namespace ZN\Request;

use URL;
use Lang;
use Config;

class URLSiteTest extends \PHPUnit\Framework\TestCase
{
    public function testUrlSite()
    {
        $this->assertStringContainsString(BASE_DIR, URL::site());
    }
    
    public function testUrlSiteFirstParameter()
    {
        $this->assertStringContainsString('/Home/test', URL::site('Home/test'));
    }

    public function testUrlSiteLangFix()
    {
        Config::services('uri', ['lang' => true]); Lang::set('en');

        $this->assertStringContainsString('/en/resources/style.css', URL::site('resources/style.css'));

        Config::services('uri', ['lang' => false]);
    }

    public function testUrlSiteWithLang()
    {
        $this->assertStringContainsString('en/Home/test', URL::lang('en')->site('Home/test'));
    }

    public function testUrlSites()
    {
        $this->assertStringContainsString('Home/test', URL::sites('Home/test'));
    }
}