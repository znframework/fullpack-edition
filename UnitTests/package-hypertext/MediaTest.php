<?php namespace ZN\Hypertext;

use Html;

class MediaTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        $this->assertStringContainsString
        (
            '<embed src="">', 
            (string) Html::embed()
        );
    }
}