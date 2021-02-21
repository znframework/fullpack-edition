<?php namespace ZN\Request;

class HttpFixTest extends \PHPUnit\Framework\TestCase
{
    public function testFixHttp()
    {
        $http = new Http;

        $this->assertEquals('http://', $http::fix());
    }

    public function testFixHttps()
    {
        $http = new Http;

        $this->assertEquals('https://', $http::fix(true));
    }
}