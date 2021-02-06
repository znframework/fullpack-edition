<?php namespace ZN\Hypertext;

use Html;

class UseElementTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        $this->assertStringContainsString
        (
            '<div class="abc"></div>', 
            (string) Html::addclass('abc')->div()
        );
    }
}