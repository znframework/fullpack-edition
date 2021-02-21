<?php namespace ZN\Hypertext;

use Html;

class ToastTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        $this->assertStringContainsString
        (
            'shown.bs.toast', 
            (string) Html::toastAutoHide('false')->tostDismissButton()->toastHeaer('My Toast Title')->toashBody(function()
            {
                echo '<p>Toast Body</p>';

            })->toast('myToast') . 
            Html::on('shown', 'console.log(1)')->toastEvent('myToast', 'show')
        );
    }
}