<?php namespace ZN\Request;

class HttpMethodTest extends \PHPUnit\Framework\TestCase
{
    public function testSelect()
    {
        $http = new Http;

        $http::name('example')->value('Example')->input('post')->insert();

        $this->assertEquals('Example', $http::input('post')->select('example'));
    }

    public function testInsert()
    {
        $http = new Http;

        $this->assertIsBool($http::input('post')->insert('example', 'Example'));
    }

    public function testInputException()
    {
        $http = new Http;

        try
        {
            $http::input('posxt');
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testDelete()
    {
        $http = new Http;

        $this->assertIsBool($http::input('post')->delete('example'));
        $this->assertIsBool($http::input('get')->delete('example'));
        $this->assertIsBool($http::input('env')->delete('example'));
        $this->assertIsBool($http::input('server')->delete('example'));
        $this->assertIsBool($http::input('request')->delete('example'));
    }
}