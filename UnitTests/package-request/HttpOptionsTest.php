<?php namespace ZN\Request;

class HttpOptionsTest extends \PHPUnit\Framework\TestCase
{
    public function testHost()
    {
        $http = new Http;

        $this->assertIsString($http::host());
    }

    public function testUserAgent()
    {
        $http = new Http;

        $this->assertIsString($http::userAgent());
    }

    public function testAccept()
    {
        $http = new Http;

        $this->assertIsString($http::accept());
    }

    public function testLanguage()
    {
        $http = new Http;

        $this->assertIsString($http::language());
    }

    public function testEncoding()
    {
        $http = new Http;

        $this->assertIsString($http::encoding());
    }

    public function testCookie()
    {
        $http = new Http;

        $this->assertIsString($http::cookie());
    }

    public function testConnection()
    {
        $http = new Http;

        $this->assertIsString($http::connection());
    }
}