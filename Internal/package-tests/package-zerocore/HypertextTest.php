<?php namespace ZN;

class HypertextTest extends ZerocoreExtends
{
    public function testAttributes()
    {
        $this->assertEquals(' style="10"', Hypertext::attributes(['style="10"']));
    }
}