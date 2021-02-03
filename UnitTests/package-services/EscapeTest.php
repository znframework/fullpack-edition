<?php namespace ZN\Services;

use CURL;

class EscapeTest extends \PHPUnit\Framework\TestCase
{
    public function testEscape()
    {
        CURL::init('https://github.com/')->returntransfer(true)->exec();

        $this->assertEquals('Hello%22', CURL::escape('Hello"'));

        $this->assertEquals('Hello"', CURL::unescape('Hello%22'));
    }

    public function testEscapeInvalidArgumentException()
    {
        $new = new \ZN\Services\CURL;

        try
        {
            $new->escape('Hello"');
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertEquals('`$this->init` parameter should contain the resource data type!', $e->getMessage());
        }
        
    }

    public function testUnEscapeInvalidArgumentException()
    {
        $new = new \ZN\Services\CURL;

        try
        {
            CURL::unescape('Hello%22');
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertEquals('`$this->init` parameter should contain the resource data type!', $e->getMessage());
        }
        
    }
}