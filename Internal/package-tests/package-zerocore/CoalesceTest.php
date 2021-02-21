<?php namespace ZN;

class CoalesceTest extends ZerocoreExtends
{
    public function testNull()
    {
        Coalesce::null($var, 'value');

        $this->assertEquals('value', $var);
    }

    public function testFalse()
    {
        $var = false;

        Coalesce::false($var, 'value');

        $this->assertEquals('value', $var);
    }

    public function testEmpty()
    {
        $var = '';

        Coalesce::empty($var, 'value');

        $this->assertEquals('value', $var);
    }
}