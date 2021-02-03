<?php namespace ZN\Prompt;

use Processor;

class PathTest extends \PHPUnit\Framework\TestCase
{
    public function testPath()
    {
        $this->assertIsString(Processor::path('php/')->exec('php -v'));
    }
}