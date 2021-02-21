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
        $return = ImageTypeCreator::from(self::dir . 'image.jpeg');

        $this->assertTrue( is_object($return) || is_resource($return) );
    }

    public function testWatermarkAligner()
    {
        $this->assertEquals([10, 10], WatermarkImageAligner::align('topleft', 50, 50, 100, 100, 10));
        $this->assertEquals([25, 10], WatermarkImageAligner::align('topcenter', 50, 50, 100, 100, 10));
        $this->assertEquals([40, 10], WatermarkImageAligner::align('topright', 50, 50, 100, 100, 10));
        $this->assertEquals([10, 25], WatermarkImageAligner::align('middleleft', 50, 50, 100, 100, 10));
        $this->assertEquals([40, 25], WatermarkImageAligner::align('middleright', 50, 50, 100, 100, 10));
        $this->assertEquals([10, 40], WatermarkImageAligner::align('bottomleft', 50, 50, 100, 100, 10));
        $this->assertEquals([25, 40], WatermarkImageAligner::align('bottomcenter', 50, 50, 100, 100, 10));
        $this->assertEquals([40, 40], WatermarkImageAligner::align('bottomright', 50, 50, 100, 100, 10));
    }
}