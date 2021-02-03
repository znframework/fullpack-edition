<?php namespace ZN\Services;

class CurlInfoTest extends \PHPUnit\Framework\TestCase
{
    public function testInfoInvalidArgument()
    {
        $curl = new CURL;

        try
        {
            $curl->info();
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertEquals('`$this->init` parameter should contain the resource data type!', $e->getMessage());
        }
    }
}