<?php namespace ZN\Console;

use File;
use Buffer;

class ControllerTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateController()
    {
        Buffer::callback(function()
        {
            new CreateController('Example');
        }); 
        
        $this->assertFileExists($file = CONTROLLERS_DIR . 'Example.php');

        if( is_file($file) )
        {
            File::delete($file);
        }
    }

    public function testDeleteController()
    {
        Buffer::callback(function()
        {
            new CreateController('Example');
            new DeleteController('Example');
        }); 

        $file = CONTROLLERS_DIR . 'Example.php';

        $this->assertFalse(is_file($file));
    }
}