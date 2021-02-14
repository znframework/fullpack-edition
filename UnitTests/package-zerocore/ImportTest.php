<?php namespace ZN;

use File;
use Buffer;
use Import;

class ImportTest extends ZerocoreExtends
{
    public function testView()
    {
        File::write(VIEWS_DIR . 'Home/example.wizard.php', '<b>{{ $data }}</b>');

        $output = Buffer::callback(function()
        {
            Import::view('Home/example');
        });
        
        $this->assertEquals('<b></b>', $output);

        $this->assertEquals('<b>Data</b>', Import::usable()->view('Home/example', ['data' => 'Data']));

        $this->assertEquals('<b>Data</b>', Import::usable()->data(['data' => 'Data'])->view('Home/example'));
    }

    public function testFont()
    {
        $output = Buffer::callback(function()
        {
            Import::something(self::resources . 'test.js');
        });
        
        $this->assertStringContainsString('test.js', $output);
        $this->assertStringContainsString('test.css', Import::something(self::resources . 'test.css', NULL, true));
        $this->assertStringContainsString('test.ttf', Import::something(self::resources . 'test.ttf', NULL, true));
        $this->assertStringContainsString('Data', Import::something(self::resources . 'test.php', ['data' => 'Data'], true));
    }
}