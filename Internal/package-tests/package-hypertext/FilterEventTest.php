<?php namespace ZN\Hypertext;

use Html;

class FilterEventTest extends \PHPUnit\Framework\TestCase
{
    public function testMake()
    {
        $this->assertStringContainsString
        (
            '$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)', 
            (string) Html::filterEvent('#myInput', '#myTable tr', 'keyup')
        );
    }
}