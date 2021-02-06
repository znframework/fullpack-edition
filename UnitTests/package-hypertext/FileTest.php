<?php namespace ZN\Hypertext;

use Form;

class FileTest extends \PHPUnit\Framework\TestCase
{
    public function testFile()
    {
        $this->assertStringContainsString
        (
            '<input type="file" name="upload[]" value="" multiple="multiple">', 
            (string) Form::file('upload', true)
        );
    }

    public function testFileMultipleOption()
    {
        $this->assertStringContainsString
        (
            '<input type="file" name="upload[]" value="" multiple="multiple">', 
            (string) Form::multiple()->file('upload')
        );
    }
}