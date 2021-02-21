<?php namespace ZN\Console;

use File;
use Buffer;
use Crontab;

class CronTest extends ConsoleExtends
{
    public function testCronSingleParameter()
    {
        Buffer::callback(function()
        {
            new Cron('Example:run', ['daily']);
        }); 

        $length = strlen(File::read(self::job));

        $this->assertTrue($length > 1);

        if( is_file(self::job) )
        {
            File::write(self::job, '');
        }
    }

    public function testCronWget()
    {
        Buffer::callback(function()
        {
            new Cron('http://www.example.com', ['daily']);
        });

        File::write(self::job, '');
    }

    public function testCronController()
    {
        Buffer::callback(function()
        {
            new Cron('Home/main', ['daily']);
        });

        File::write(self::job, '');
    }

    public function testCronMultiParameter()
    {
        Buffer::callback(function()
        {
            new Cron('Example:run2', ['day', 'saturday', 'clock', '12:00']);
        }); 

        $length = strlen(File::read(self::job));

        $this->assertTrue($length > 1);

        if( is_file(self::job) )
        {
            File::write(self::job, '');
        }
    }

    public function testCronList()
    {
        Buffer::callback(function()
        {
            new Cron('Example:run2', ['day', 'saturday', 'clock', '12:00']);
        });

        $array = Crontab::listArray();

        $this->assertTrue(count($array) > 0);

        if( is_file(self::job) )
        {
            File::write(self::job, '');
        }
    }

    public function testCronListWithConsole()
    {
        Buffer::callback(function()
        {
            new CronList;
        });
    }

    public function testRemoveCron()
    {
        Buffer::callback(function()
        {
            new Cron('Example:run2', ['day', 'saturday', 'clock', '12:00']);
            new RemoveCron('Example');
        });

        $array = Crontab::listArray();

        $this->assertSame(0, count($array));
    }
}