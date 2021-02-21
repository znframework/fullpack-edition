<?php namespace ZN\Hypertext;

use Html;

class GridTest extends \PHPUnit\Framework\TestCase
{
    public function testString()
    {
        $this->assertStringContainsString
        (
            '<div class="col-sm-8">column size 8</div>', 
            (string) Html::colsm4('column size 4')->colsm4('column size 4')->colsm4('column size 4')
            ->colsm8('column size 8')->colsm2('column size 2')->colsm2('column size 2')
        );
    }

    public function testCallable()
    {
        $this->assertStringContainsString
        (
            '<div class="col-lg-6">col size 6</div>', 
            (string) Html::collg6(function(){ echo 'col size 6'; })->collg6(function(){ echo 'col size 6'; })
        );
    }

    public function testLayout()
    {
        $this->assertEquals
        (
            '',
            (string) Html::fluid()
        );
    }

    public function testContainer()
    {
        $this->assertStringContainsString
        (
            '<div class="container">',
            (string) Html::startContainerDiv()
        );
    }

    public function testContainerFluid()
    {
        $this->assertStringContainsString
        (
            '<div class="container-fluid">',
            (string) Html::startFluidContainerDiv()
        );
    }

    public function testStartRowDiv()
    {
        $this->assertStringContainsString
        (
            '<div class="row">',
            (string) Html::startRowDiv()
        );
    }

    public function testEndRowDiv()
    {
        $this->assertStringContainsString
        (
            '</div>',
            (string) Html::endDiv()
        );
    }

    public function testStartColumnDiv()
    {
        $this->assertStringContainsString
        (
            '<div class="col-sm-2">',
            (string) Html::startColumnDiv('sm-2')
        );
    }
}