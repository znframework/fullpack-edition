<?php namespace ZN\Hypertext;

use Form;

class TextareaTest extends \PHPUnit\Framework\TestCase
{
    public function testTextarea()
    {
        $this->assertStringStartsWith
        (
            '<textarea cols="50" rows="5" name="address">Address</textarea>', 
            (string) Form::cols(50)->rows(5)->textarea('address', 'Address')
        );
    }
}