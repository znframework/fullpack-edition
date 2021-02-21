<?php namespace ZN\Prompt;

use Processor;

class ReturnTest extends \PHPUnit\Framework\TestCase
{
    public function testReturn()
    {
        Processor::exec('php -v');

        $this->assertIsInt(Processor::return());
    }
}