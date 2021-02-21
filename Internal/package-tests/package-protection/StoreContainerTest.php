<?php namespace ZN\Protection;

class StoreContainerTest extends ProtectionExtends
{
    public function testContainer()
    {
        $store = new Store(new Json);

        $encode = $store->encode(['foo' => 'Foo']);

        $this->assertEquals((object) ['foo' => 'Foo'], $store->decode($encode));
    }
}