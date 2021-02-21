<?php namespace ZN\Prompt;

use Processor;

class TypeTest extends \PHPUnit\Framework\TestCase
{
    public function testType()
    {
        $this->assertContains(Processor::type(), ['cli', 'cgi', php_sapi_name()]);
    }
}