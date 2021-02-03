<?php namespace ZN\Prompt;

use Processor;

class ExecTest extends \PHPUnit\Framework\TestCase
{
    public function testExec()
    {
        Processor::exec('php -v');

        $this->assertIsArray(Processor::output());
    }
}