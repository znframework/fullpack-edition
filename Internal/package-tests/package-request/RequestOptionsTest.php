<?php namespace ZN\Request;

use Request;

class RequestOptionsTest extends \PHPUnit\Framework\TestCase
{
    public function testScheme()
    {
        $this->assertIsString(Request::scheme());
    }

    public function testMethod()
    {
        $this->assertIsString(Request::method());
    }

    public function testUri()
    {
        $this->assertIsString(Request::uri());
    }

    public function testTime()
    {
        $this->assertIsInt(Request::time());
    }

    public function testTimeFloat()
    {
        $this->assertIsFloat(Request::timeFloat());
    }
}