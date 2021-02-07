<?php namespace ZN\Generator;

use Generate;

class ProjectTest extends GeneratorExtends
{
    public function testProjectInvalidName()
    {
        $this->assertFalse(Generate::project('invalid name'));
    }
}