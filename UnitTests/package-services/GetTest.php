<?php namespace ZN\Services;

use XML;
use Restful;

class GetTest extends \PHPUnit\Framework\TestCase
{
    public function testGet()
    {
        $this->assertIsObject(Restful::get('https://repo.packagist.org/p/znframework/znframework.json'));
    }

    public function testGetCallOptions()
    {
        $this->assertIsObject(Restful::returntransfer(1)->get('https://repo.packagist.org/p/znframework/znframework.json'));
    }

    public function testGetWithUrl()
    {
        $this->assertIsObject(Restful::url('https://repo.packagist.org/p/znframework/znframework.json')->get());
    }

    public function testGetWithSSLVerifyPeer()
    {
        $this->assertIsObject(Restful::sslVerifypeer(false)->url('https://repo.packagist.org/p/znframework/znframework.json')->get());
    }

    public function testGetXMLResponse()
    {
        $this->assertIsObject(Restful::get('https://doc.storage.googleapis.com/'));
    }
}