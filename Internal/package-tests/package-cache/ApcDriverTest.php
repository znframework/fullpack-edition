<?php namespace ZN\Cache;

class ApcDriverTest extends CacheExtends
{
    public function testInsert()
    {
        try
        {
            $this->apc()->insert('example', 1);
    
            $this->assertEquals(1, $this->apc()->select('example'));
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }      
    }

    public function testDelete()
    {
        try
        {
            $this->apc()->insert('example', 1);

            $this->apc()->delete('example');
    
            $this->assertEmpty($this->apc()->select('example'));
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }      
    }

    public function testDecrement()
    {
        try
        {
            $this->apc()->insert('a', 2);

            $this->apc()->decrement('a');
    
            $this->assertEquals(1, $this->apc()->select('a'));
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }   
    }

    public function testIncrement()
    {
        try
        {
            $this->apc()->insert('a', 1);

            $this->apc()->increment('a');
    
            $this->assertEquals(2, $this->apc()->select('a'));
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }   
    }

    public function testInfo()
    {
        try
        {
            $this->apc()->insert('a', 1);
    
            $this->apc()->info();
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        } 
    }

    public function testClean()
    {
        try
        {
            $this->apc()->insert('a', 1);
    
            $this->apc()->clean();

            $this->assertEmpty($this->apc()->select('a'));
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        } 
    }
}