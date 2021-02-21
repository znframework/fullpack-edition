<?php namespace ZN\Image;

use GD;

class InfoTest extends Test\GDExtends
{
    public function testInfo()
    {
        $this->assertIsArray(GD::info());
    }

    public function testSize()
    {
        $this->assertIsObject(GD::size(self::img));
    }

    public function testSizeString()
    {
        $this->assertIsObject(GD::size(file_get_contents(self::img)));
    }

    public function testSizeException()
    {
        try
        {
            GD::size('abc');
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertStringContainsString('[file]', $e->getMessage());
        }
    }
    
    public function testMime()
    {
        $this->assertIsString(GD::mime());
    }}