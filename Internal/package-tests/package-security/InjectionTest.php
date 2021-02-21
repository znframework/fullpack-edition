<?php namespace ZN\Security;

use Security;

class InjectionTest extends \PHPUnit\Framework\TestCase
{
    public function testInjectionEncode()
    {
        $this->assertEquals('ZN\" Framework', Security::injectionEncode('ZN" Framework'));
    }

    public function testInjectionDecode()
    {
        $this->assertEquals('ZN" Framework', Security::injectionDecode('ZN\\" Framework'));
    }

    public function testEscapeStringEncode()
    {
        $this->assertEquals('ZN\" Framework', Security::escapeStringEncode('ZN" Framework'));
    }

    public function testEscapeStringDecode()
    {
        $this->assertEquals('ZN" Framework', Security::escapeStringDecode('ZN\\" Framework'));
    }
}