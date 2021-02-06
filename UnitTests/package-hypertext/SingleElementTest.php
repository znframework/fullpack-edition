<?php namespace ZN\Hypertext;

use Html;

class SingleElementTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        $this->assertStringContainsString
        (
            '<hr id="1">', 
            (string) Html::hr(['id' => 1])
        );
    }
}