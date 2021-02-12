<?php namespace ZN;

use View;

class ControllerTest extends ZerocoreExtends
{
    public function testGet()
    {
        $this->assertEquals('eca07335a33c5aeb5e1bc7c98b4b9d80', $this->controllerMock->encode->type('param'));
        
        $this->assertEquals('Example', $this->controllerMock->run());

        $this->assertNull($this->controllerMock->restart());
    }
}