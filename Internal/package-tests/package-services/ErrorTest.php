<?php namespace ZN\Services;

class ErrorTest extends \PHPUnit\Framework\TestCase
{
    public function testError()
    {
        $curl = new CURL;

        $curl->init('xyz')->exec();

        $this->assertIsString($curl->error());
    }

    public function testErrno()
    {
        $curl = new CURL;

        $curl->init('xyz')->exec();

        $this->assertIsInt($curl->errno());
    }

    public function testErrorInvalidArgument()
    {
        $curl = new CURL;

        try
        {
            $curl->error();
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertEquals('`$this->init` parameter should contain the resource data type!', $e->getMessage());
        }

        try
        {
            $curl->errno();
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertEquals('`$this->init` parameter should contain the resource data type!', $e->getMessage());
        }
    }

    public function testErrorValue()
    {
        $curl = new CURL;

        $this->assertIsString($curl->errval(0));
    }
}