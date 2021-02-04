<?php namespace ZN\XML;

class BuilderTest extends \ZN\Test\GlobalExtends
{
    public function testBuild()
    {
        $builder = new Builder;

        $output = $builder->version(1)->encoding('utf-8')->do
        ([  
            'name'  => 'media', 
            'attr'  => ['id' => 1], 
            'child' => 
            [ 
                ['name' => 'video', 'attr' => ['id' => 2], 'content' => 'Vidyo']
            ]
        ]);

        $this->assertStringContainsString('<video id="2">Vidyo</video>', $output);
    }   

    public function testBuildLocalParameters()
    {
        $builder = new Builder;

        $output = $builder->do
        ([  
            'name'  => 'media', 
            'attr'  => ['id' => 1], 
            'child' => 
            [ 
                ['name' => 'video', 'attr' => ['id' => 2], 'content' => 'Vidyo']
            ]
        ], 1, 'utf-8');

        $this->assertStringContainsString('<video id="2">Vidyo</video>', $output);
    } 
}