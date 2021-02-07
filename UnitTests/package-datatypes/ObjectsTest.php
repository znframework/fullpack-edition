<?php namespace ZN\DataTypes;

class ObjectsTest extends \PHPUnit\Framework\TestCase
{
    public function testGetFirst()
    {
        $object = new Objects(['foo' => 'Foo', 'bar' => ['zoo' => 'Zoo']]);

        $this->assertEquals('Zoo', $object->bar->zoo);
    }
}