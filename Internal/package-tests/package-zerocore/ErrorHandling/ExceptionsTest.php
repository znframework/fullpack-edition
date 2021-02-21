<?php namespace ZN\ErrorHandling;

use File;
use Buffer;

class ExceptionsTest extends \PHPUnit\Framework\TestCase
{
    public function testThrows()
    {
        $result = Buffer::callback(function()
        {
            ob_start();
            
            Exceptions::throws('Database', 'tableNotExistsError', 'table');
        }); 

        $this->assertStringContainsString('[Exceptions::throws()] `table` table is not exists!', $result);
    }

    public function testContinue()
    {
       
        ob_start();
            
        $this->assertStringContainsString('Database', Exceptions::continue('Database', 'Database.php', 11));
    }

    public function testHandler()
    {  
        $this->assertNull(Exceptions::handler());
    }

    public function testRestore()
    {  
        $this->assertIsBool(Exceptions::restore());
    }

    public function testTable()
    {  
        try
        {
            File::reglace('unknownfile', 'abc', 'xyzz');
        }
        catch( \Exception $e )
        {
            $result = Buffer::callback(function() use($e)
            { 
                ob_start();
                        
                Exceptions::table($e);
            }); 

            $this->assertIsString($result);
        }
       
    }
}