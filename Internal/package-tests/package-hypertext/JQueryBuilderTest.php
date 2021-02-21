<?php namespace ZN\Hypertext;

class JQueryBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        $builder = new JQueryBuilder;

        $output = $builder->selector('.selector')
                          ->click(function()
                          {
                              echo 'console.log(1)';
                          })
                          ->bind('keydown', function()
                          {
                              echo 'console.log(2)';
                          })
                          ->attr('style', 'color:red');
        
        $this->assertStringContainsString('$(".selector").click(function(data)', $output);
    }
}