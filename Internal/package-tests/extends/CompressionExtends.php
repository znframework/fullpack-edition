<?php namespace ZN\Compression;

use File;
use Folder;
use Compress;

class CompressionExtends extends \ZN\Test\GlobalExtends
{
    const directory = self::default . 'package-compression/';
    const file      = self::directory . 'test.txt';
    const path      = self::default . 'package-compression/resources/';

    public function extract($driver)
    {
        try
        {
            Compress::driver($driver)->extract(self::path . 'example.rar', $target = self::path . 'rar');

            $this->assertDirectoryExists($target);

            Folder::delete($target);
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }  
        
        try
        {
            Compress::extract('unknownpath');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        } 
    }

    public function do($driver)
    {
        try
        {
            $compress = Compress::driver($driver)->do('Example Data');

            $this->assertIsString($compress);
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }   
    }

    public function undo($driver)
    {
        try
        {
            $compress = Compress::driver($driver)->do('Example Data');

            $undo = Compress::driver($driver)->undo($compress);
    
            $this->assertIsString('Example Data', $undo);
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }   
    }

    public function write($driver)
    {
        try
        {
            Compress::driver($driver)->write(self::file, 'Example Data');

            $this->assertIsString(File::read(self::file));
    
            File::delete(self::file);
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }   

        try
        {
            Compress::driver($driver)->write(self::file . 'unknown', 'Example Data');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function read($driver)
    {
        try
        {
            Compress::driver($driver)->write(self::file, 'Example Data');

            $this->assertEquals('Example Data', Compress::driver($driver)->read(self::file));

            File::delete(self::file);
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }   

        try
        {
            Compress::driver($driver)->read(self::file . 'unknown');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}