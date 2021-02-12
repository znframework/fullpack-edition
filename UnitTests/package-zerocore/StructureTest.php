<?php namespace ZN;

class StructureTest extends ZerocoreExtends
{
    public function testDefines()
    {
        Structure::defines();

        $this->assertIsArray(STRUCTURE_DATA);
    }
}