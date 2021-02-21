<?php namespace ZN\Hypertext;

use Html;

class PopoverTest extends \PHPUnit\Framework\TestCase
{
    public function testPopover()
    {
        $this->assertStringContainsString
        (
            ' data-toggle="popover"', 
            (string) Html::class('btn btn-danger')->popover('right', 'ZN Framework')->button('NAME') .
                     Html::popoverEvent('all', ['delay' => 100])
        );
    }

    public function testPopoverOn()
    {
        $this->assertStringContainsString
        (
            'popover({"delay":100}).on', 
            (string) Html::class('btn btn-danger')->popover('right', 'ZN Framework')->button('NAME') .
                     Html::on('shown', 'console.log(1)')->popoverEvent('all', ['delay' => 100])
        );
    }

    public function testPopoverEventItSelf()
    {
        $this->assertStringContainsString
        (
            ' data-toggle="popover"', 
            (string) Html::class('btn btn-danger')->popover('right', 'ZN Framework')->button('NAME') .
                     Html::popover('all', ['delay' => 100])
        );
    }
}