<?php namespace ZN\Request;

use Server;

class ServerTest extends \PHPUnit\Framework\TestCase
{
    public function testServer()
    {
        $this->assertEquals($_SERVER['SCRIPT_NAME'], Server::scriptName());
    }

    public function testServerAll()
    {
        $this->assertIsArray(Server::all());
    }

    public function testServerWithMethod()
    {
        $method = new Method;

        $this->assertEmpty($method::server('host'));
    }

    public function testServerWithMethodSecondParameter()
    {
        $method = new Method;

        $this->assertEquals('Example', $method::server('example', 'Example'));
    }

    public function testOs()
    {
        $this->assertIsString(Server::os());
    }

    public function testServerArrayParameter()
    {
        $_SERVER['example'] = ['a', 'b'];

        $this->assertIsArray(Server::data(NULL));
    }

    public function testServerStringParameter()
    {
        $_SERVER['example'] = 'Example';

        $this->assertEquals('Example', Server::data('example'));
    }
}