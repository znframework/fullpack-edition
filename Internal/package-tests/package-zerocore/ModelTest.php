<?php namespace ZN;

class ModelTest extends ZerocoreExtends
{
    public function testGet()
    {
        $this->assertEquals('eca07335a33c5aeb5e1bc7c98b4b9d80', $this->modelMock->encode->type('param'));
        $this->assertEquals('eca07335a33c5aeb5e1bc7c98b4b9d80', $this->modelMock->encode->type('param'));
    }
}