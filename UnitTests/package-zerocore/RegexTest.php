<?php namespace ZN;

class RegexTest extends ZerocoreExtends
{
    public function testClassic2Special()
    {
        $this->assertEquals('{nonWord}', \Regex::classic2special('/\w+/'));
    }

    public function testMatch()
    {
        $this->assertEquals(['ZN'], \Regex::match('{word}', 'ZN', 'i'));
    }

    public function testMatchAll()
    {
        $this->assertEquals([['ZN']], \Regex::matchAll('{word}', 'ZN', 'i'));
    }

    public function testGroup()
    {
        $this->assertEquals('(ZN)', \Regex::group('ZN'));
    }

    public function testRecount()
    {
        $this->assertEquals('{ZN}', \Regex::recount('ZN'));
    }

    public function testTo()
    {
        $this->assertEquals('[ZN]', \Regex::to('ZN'));
    }

    public function testQuote()
    {
        $this->assertEquals('ZN', \Regex::quote('ZN'));
    }
}