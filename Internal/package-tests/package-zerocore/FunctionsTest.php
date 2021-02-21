<?php namespace ZN;

class FunctionsTest extends ZerocoreExtends
{
    public function testAgainst()
    {
        $return = against(1, 
        [
            1 => function(){ return 1; },
            'default' => 2
        ]);

        $this->assertEquals(1, $return);

        $return = against(1, 
        [
            1 => 1,
            'default' => 2
        ]);

        $this->assertEquals(1, $return);

        $return = against(2, 
        [
            1 => 1,
            'default' => 2
        ]);

        $this->assertEquals(2, $return);
    }

    public function testOutput()
    {
        $this->assertStringContainsString('hello', Output('hello', [], true));
    }

    public function testRedirect()
    {
        $this->assertNull(redirect('home/main'));
    }
}