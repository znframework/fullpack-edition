<?php namespace ZN\Hypertext;

use Form;

class PasswordTest extends \PHPUnit\Framework\TestCase
{
    public function testPassword()
    {
        $this->assertStringStartsWith
        (
            '<input type="password" maxlength="10" name="password" value="*****">', 
            (string) Form::password('password', '*****', ['maxlength' => 10])
        );
    }
}