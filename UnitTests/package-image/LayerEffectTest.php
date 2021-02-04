<?php namespace ZN\Image;

use GD;

class LayerEffectTest extends Test\GDExtends
{
    public function testLayerEffect()
    {
        GD::canvas(self::img)
          ->layerEffect('replace')
          ->generate('png', $generateFile = self::dir . 'image-layereffect.png');

        $this->assertFileExists($generateFile);
    }
}