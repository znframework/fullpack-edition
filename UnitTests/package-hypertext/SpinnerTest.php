<?php namespace ZN\Hypertext;

use Html;

class SpinnerTest extends \PHPUnit\Framework\TestCase
{
    public function testSpinner()
    {
        $this->assertStringContainsString
        (
            '<div class="spinner-grow text-danger spinner-grow-sm"></div>', 
            (string) Html::spinner('grow', 'danger', 'sm')
        );
    }

    public function testBorder()
    {
        $this->assertStringContainsString
        (
            'spinner-border', 
            (string) Html::spinnerBorder('danger', 'sm')
        );
    }

    public function testGrow()
    {
        $this->assertStringContainsString
        (
            '<div class="spinner-grow text-danger spinner-grow-sm"></div>', 
            (string) Html::spinnerGrow('danger', 'sm')
        );
    }
}