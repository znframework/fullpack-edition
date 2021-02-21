<?php namespace ZN;

use File;
use Theme;
use Folder;
use Buffer;
use Import;
use Masterpage;

class ImportTest extends ZerocoreExtends
{
    public function testView()
    {
        Folder::create(THEMES_DIR . 'Default/');

        Theme::active('Default');
        Theme::matchElement();

        File::copy(self::resources . 'test.js', THEMES_DIR . 'Default/test.js');
        File::copy(self::resources . 'test.css', THEMES_DIR . 'Default/test.css');
        File::copy(self::resources . 'test.ttf', THEMES_DIR . 'Default/test.ttf');
        
        File::write(VIEWS_DIR . 'Home/example.wizard.php', '<b>{{ $data }}</b>');

        $output = Buffer::callback(function()
        {
            Import::view('Home/example');
        });
        
        $this->assertEquals('<b></b>', $output);

        $this->assertEquals('<b>Data</b>', Import::usable()->view('Home/example', ['data' => 'Data']));

        $this->assertEquals('<b>Data</b>', Import::usable()->data(['data' => 'Data'])->view('Home/example'));
    }

    public function testSomething()
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

    public function testFont()
    {
        $output = Buffer::callback(function()
        {
            Import::font('test');
        });
        
        $this->assertIsString($output);
        $this->assertIsString(Import::font('test', true));
    }

    public function testHandload()
    {
        File::write(HANDLOAD_DIR . 'example.php', 'Example');

        $output = Buffer::callback(function()
        {
            Import::handload('example');
        });
        
        $this->assertStringContainsString('Example', $output);
    }

    public function testPlugin()
    {
        File::copy(self::resources . 'test.js', PLUGINS_DIR . 'test.js');
        File::copy(self::resources . 'test.css', PLUGINS_DIR . 'test.css');
        File::copy(self::resources . 'test.ttf', PLUGINS_DIR . 'test.ttf');

        $output = Buffer::callback(function()
        {
            Import::plugin('test.js', 'test.css');
        });
        
        $this->assertStringContainsString('test.js', $output);
        $this->assertStringContainsString('test.js', Import::plugin('test.js', 'test.css', true));
    }

    public function testTheme()
    {
        $output = Buffer::callback(function()
        {
            Import::theme('Default');
        });
        
        Import::theme('Default', false, true);
    }

    public function testMasterpage()
    {
        Masterpage::body('body')
                  ::head('head')
                  ::title('title')
                  ::meta([ 'name:description' => 'Description', 'name:keywords' => 'Example, key, words'])
                  ::attributes(['body' => ['id' => 1]])
                  ::theme(['name' => ['test.css']])
                  ::plugin(['name' => ['test.js']])
                  ::content(['language' => 'tr', 'charset'  => ['utf-8']]);
    }
}