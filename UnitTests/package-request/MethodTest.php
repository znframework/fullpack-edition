<?php namespace ZN\Request;

use Method;

class MethodTest extends \PHPUnit\Framework\TestCase
{
    public function testMethod()
    {
        Method::post('example', 'Example');

        $this->assertEquals('Example', Method::post('example'));
    }

    public function testJsonCheck()
    {
        Method::post('example', $json = json_encode(['a', 'b']));

        $this->assertEquals($json, Method::post('example'));
    }

    public function testArrayParameter()
    {
        Method::post('example', ['a', 'b']);

        $this->assertIsArray(Method::post('example'));
    }
    
    public function testInvalidMethod()
    {
        $this->assertTrue(Method::Post('example', 'Example'));
    }

    public function testDelete()
    {
        Method::delete('post', 'example');
    }
}