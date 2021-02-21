<?php namespace ZN\Hypertext;

class AjaxBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        $builder = new AjaxBuilder;

        $output = $builder->tag(true)
                    ->url('foo/bar')
                    ->type('post')
                    ->complete(function()
                    {
                        echo '{data=1}';
                    })
                    ->data(function()
                    {
                        echo '{data=1}';
                    })
                    ->data('data=1')
                    ->done(function()
                    {
                        echo 'console.log(1)';
                    });
        
        $this->assertStringContainsString('data:{data=1}', $output);
    }
}