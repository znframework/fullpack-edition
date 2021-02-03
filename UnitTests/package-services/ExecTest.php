<?php namespace ZN\Services;

use CURL;

class ExecTest extends \PHPUnit\Framework\TestCase
{
    public function testExec()
    {
        CURL::init('https://github.com/')
            ->option('returntransfer', true)
            ->option('header', false)
            ->exec();

        $this->assertEquals('https://github.com/', CURL::info()['url']);
    }

    public function testSingleExecuteInvalidException()
    {
        $curl = new \ZN\Services\CURL;

        try
        {
            $curl->exec();
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertEquals('`$this->init` parameter should contain the resource data type!', $e->getMessage());
        }
    }
}