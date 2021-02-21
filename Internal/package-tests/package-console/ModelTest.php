<?php namespace ZN\Console;

use File;
use Folder;
use Buffer;

class ModelTest extends \ZN\Database\DatabaseExtends
{
    public function testCreateModel()
    {
        Buffer::callback(function()
        {
            new CreateModel('Example1');
        });
        
        $this->assertFileExists($file = MODELS_DIR . 'Example1.php');

        if( is_file($file) )
        {
            File::delete($file);
        }
    }

    public function testCreateGrandModel()
    {
        Buffer::callback(function()
        {
            new CreateGrandModel('Example');
        });

        $this->assertFileExists($file = MODELS_DIR . 'Example.php');

        if( is_file($file) )
        {
            File::delete($file);
        }
    }

    public function testCreateGrandVision()
    {
        Buffer::callback(function()
        {
            new CreateGrandVision('testdb');
        });       
    }

    public function testDeleteGrandVision()
    {
        Buffer::callback(function()
        {
            new DeleteGrandVision('testdb');
        });    
    }

    public function testDeleteModel()
    {
        Buffer::callback(function()
        {
            new CreateModel('Example');
            new DeleteModel('Example');
        });     

        $file = MODELS_DIR . 'Example.php';

        $this->assertFalse(is_file($file));
    }
}