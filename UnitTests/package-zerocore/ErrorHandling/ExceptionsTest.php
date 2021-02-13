<?php namespace ZN\ErrorHandling;

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
}