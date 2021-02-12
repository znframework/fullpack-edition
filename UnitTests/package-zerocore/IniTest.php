<?php namespace ZN;

class IniTest extends ZerocoreExtends
{
    public function testCall()
    {
        $this->assertNull(Ini::postMaxSize('10M'));
        $this->assertIsString(Ini::postMaxSize());

        try
        {
            Ini::unknown(5);
        }
        catch( \Exception $e )
        {
            $this->assertEquals('The [unknown] method is not a valid ini configuration!', $e->getMessage());
        }
    }

    public function testSet()
    {
        $this->assertIsString(Ini::set('post_max_size', '10M'));
    }

    public function testGet()
    {
        $this->assertIsString(Ini::get('post_max_size'));
    }

    public function testRestore()
    {
        $this->assertNull(Ini::restore('post_max_size'));
    }

    public function testAll()
    {
        $this->assertIsArray(Ini::getAll());
    }
}