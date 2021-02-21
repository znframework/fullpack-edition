<?php namespace ZN\Services;

use CURL;

class PauseTest extends \PHPUnit\Framework\TestCase
{
    public function testPause()
    {
        CURL::init('https://github.com/')->returntransfer(true)->exec();

        $this->assertIsInt(CURL::pause());
    }

    public function testPauseInvalidArgumentException()
    {
        $new = new \ZN\Services\CURL;

        try
        {
            $new->pause();
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertEquals('`$this->init` parameter should contain the resource data type!', $e->getMessage());
        }
        
    }
}