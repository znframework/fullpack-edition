<?php namespace ZN\Hypertext;

use Form;

class HiddenTest extends \PHPUnit\Framework\TestCase
{
    public function testMakeMultiple()
    {
        $this->assertStringStartsWith('<input type="hidden" name="a" id="a" value="a">', (string) Form::hidden(['a' => 'a']));
    }
}