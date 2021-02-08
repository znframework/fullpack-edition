<?php namespace ZN\Console;

use Buffer;

class RestorationTest extends \PHPUnit\Framework\TestCase
{
    public function testStartAndEndRestorationDelete()
    {
        $dir = PROJECTS_DIR . 'MERestore';

        Buffer::callback(function()
        {
            new CreateProject('ME');
            new StartRestoration('ME');
            new EndRestoration('ME');
            new EndRestorationDelete('ME');
            new DeleteProject('ME');
        });
      
        $this->assertFalse(is_dir($dir));
    }
}