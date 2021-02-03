<?php namespace ZN\Request;

use URL;

class URLParseTest extends \PHPUnit\Framework\TestCase
{
    public function testParse()
    {
        $this->assertEquals('www.example.com', URL::parse('http://www.example.com/'));
    }
}