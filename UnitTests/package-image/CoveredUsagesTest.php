<?php namespace ZN\Image;

class CoveredUsagesTest extends Test\GDExtends
{
    public function testCalculatorSizeGreatherThanB()
    {
        $a = 10;
        $b = 5;

        CoordinateRateCalculator::run(100, $a, $b);

        $this->assertEquals('200-100', $a . '-' . $b);
    }

    public function testTypeJPEGCreator()
    {
        $this->assertTrue(ImageTypeCreator::create(imagecreatetruecolor(100, 100), self::dir . 'image.jpeg'));
    }

    public function testTypeJPEGFrom()
    {
        $this->assertIsObject(ImageTypeCreator::from(self::dir . 'image.jpeg'));
    }
}