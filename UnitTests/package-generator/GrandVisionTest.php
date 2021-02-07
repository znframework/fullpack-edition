<?php namespace ZN\Generator;

use Folder;
use Generate;

class GrandVisionTest extends GeneratorExtends
{
    public function testGrandVision()
    {
        Generate::grandVision();

        Folder::delete(MODELS_DIR . 'Visions/');
    }

    public function testDeleteGrandVision()
    {
        $this->assertNull(Generate::deleteVision('*'));
    }

    public function testDeleteGrandVisionSome()
    {
        $this->assertNull(Generate::deleteVision('testdb', ['persons']));

        Generate::grandVision();
    }
}