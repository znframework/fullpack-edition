<?php namespace ZN\Protection;

use Separator;

class SeparatorEncodeTest extends ProtectionExtends
{
    public function testEncode()
    {
        $this->assertSame('foo+-?||?-+Foo|?-++-?|bar+-?||?-+Bar', Separator::encode(['foo' => 'Foo', 'bar' => 'Bar']));
    }
}