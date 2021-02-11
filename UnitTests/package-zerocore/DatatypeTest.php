<?php namespace ZN;

class DatatypeTest extends ZerocoreExtends
{
    public function testDivide()
    {
        $this->assertEquals(['zn', 'framework'], Datatype::divide('zn|framework', '|', 'all'));
        $this->assertEquals('framework|example', Datatype::divide('zn|framework|example', '|', 1, 'all'));
        $this->assertEquals('', Datatype::divide('zn|framework|example', '|', 3, 5));
    }
}