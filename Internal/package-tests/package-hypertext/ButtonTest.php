<?php namespace ZN\Hypertext;

use Form;

class ButtonTest extends \PHPUnit\Framework\TestCase
{
    public function testSubmit()
    {
        $this->assertStringStartsWith
        (
            '<input type="submit" name="sendSubmit" value="Send">', 
            (string) Form::submit('sendSubmit', 'Send')
        );
    }

    public function testReset()
    {
        $this->assertStringStartsWith
        (
            '<input type="reset" name="clear" value="Clear">', 
            (string) Form::reset('clear', 'Clear')
        );
    }

    public function testButton()
    {
        $this->assertStringStartsWith
        (
            '<input type="button" name="sendButton" value="Send">', 
            (string) Form::button('sendButton', 'Send')
        );
    }

    public function testRadio()
    {
        $this->assertStringStartsWith
        (
            '<input type="radio" name="gender" value="Female" checked="checked">', 
            (string) Form::checked()->radio('gender', 'Female')
        );
    }

    public function testCheckbox()
    {
        $this->assertStringContainsString
        (
            '<input type="checkbox" name="trueType" value="true" checked="checked">', 
            (string) Form::checked()->checkbox('trueType', 'true')
        );
    }
}