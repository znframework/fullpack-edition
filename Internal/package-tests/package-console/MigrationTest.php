<?php namespace ZN\Console;

use File;
use Folder;
use Buffer;

class MigrationTest extends ConsoleExtends
{
    const file             = MODELS_DIR . 'Migrations/Students.php';
    const versionFile      = MODELS_DIR . 'Migrations/StudentsVersion/002.php';
    const versionDirectory = MODELS_DIR . 'Migrations/StudentsVersion';

    public function testCreateMigration()
    {
        Buffer::callback(function()
        {
            new CreateMigration('Students', [0]);
        });
        
        $this->assertFileExists($file = self::file);

        if( is_file($file) )
        {
            File::delete($file);
        }
    }

    public function testCreateMigrationOtherVersion()
    {
        Buffer::callback(function()
        {
            new CreateMigration('Students', [2]);
        });

        $this->assertFileExists($file = self::versionFile);

        if( is_dir($dir = self::versionDirectory) )
        {
            Folder::delete($dir);
        }
    }

    public function testUpDownMigration()
    {
        Buffer::callback(function()
        {
            new CreateMigration('Students', [0]);
            new UpMigration('Students', []);
            new DownMigration('Students', []);
            new DeleteMigration('Students');
        }); 
    }

    public function testMultiUpDownMigration()
    {
        Buffer::callback(function()
        {
            new CreateMigration('Students', [0]);
            new MultiupMigration('Students', []);
            new MultidownMigration('Students', []);
            new DeleteMigration('Students');
        }); 
    }

    public function testDeleteMigration()
    {
        Buffer::callback(function()
        {
            new CreateMigration('Students');
            new DeleteMigration('Students');
        });       

        $file = self::file;

        $this->assertFalse(is_file($file));
    }

    public function testDeleteMigrationAll()
    {
        Buffer::callback(function()
        {
            new CreateMigration('Students');
            new DeleteMigrationAll();
        });  

        $file = self::file;

        $this->assertFalse(is_file($file));
    }
}