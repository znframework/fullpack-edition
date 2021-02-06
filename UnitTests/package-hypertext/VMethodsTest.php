<?php namespace ZN\Hypertext;

use Form;

class VMethodsTest extends \PHPUnit\Framework\TestCase
{
    public function testUrl()
    {
        $this->assertStringContainsString('pattern="^(\w+:)?//.*"', (string) Form::vUrl()->text('name'));
    }

    public function testNumeric()
    {
        $this->assertStringContainsString('pattern="^[0-9]+$"', (string) Form::vNumeric()->text('name'));
    }

    public function testAlnum()
    {
        $this->assertStringContainsString('pattern="^([a-zA-Z]|[0-9])+$"', (string) Form::vAlnum()->text('name'));
    }

    public function testRequired()
    {
        $this->assertStringContainsString('pattern="^.+$"', (string) Form::vRequired()->text('name'));
    }

    public function testCaptcha()
    {
        $this->assertStringContainsString('ZNValidationCaptcha', (string) Form::vCaptcha()->text('name'));
    }

    public function testMatch()
    {
        $this->assertStringContainsString('ZNValidationMatch', (string) Form::vMatch('target')->text('name'));
    }

    public function testMatchPassword()
    {
        $this->assertStringContainsString('ZNValidationMatch', (string) Form::vMatchPassword('target')->text('name'));
    }

    public function testPattern()
    {
        $this->assertStringContainsString('pattern="[a-z]+"', (string) Form::vPattern('[a-z]+')->text('name'));
    }

    public function testIdendity()
    {
        $this->assertStringContainsString('ZNValidationIdentity', (string) Form::vIdentity()->text('name'));
    }

    public function testLimit()
    {
        $this->assertStringContainsString('pattern=".{10,20}"', (string) Form::vLimit(10, 20)->text('name'));
    }
}