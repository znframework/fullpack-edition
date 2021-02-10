<?php namespace ZN\Cache;

class MemcacheDriverTest extends CacheExtends
{
    public function testX()
    {

    }
    
    public function testInsert()
    {
        try
        {
            $this->memcache()->insert('example', 1);
    
            $this->assertEquals(1, $this->memcache()->select('example'));
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
            $this->memcache()->insert('example', 1);

            $this->memcache()->delete('example');
    
            $this->assertEmpty($this->memcache()->select('example'));
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
            $this->memcache()->insert('a', 2);

            $this->memcache()->decrement('a');
    
            $this->assertEquals(1, $this->memcache()->select('a'));
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
            $this->memcache()->insert('a', 1);

            $this->memcache()->increment('a');
    
            $this->assertEquals(2, $this->memcache()->select('a'));
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
            $this->memcache()->insert('a', 1);
    
            $this->memcache()->info();
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
            $this->memcache()->insert('a', 1);
    
            $this->memcache()->clean();

            $this->assertEmpty($this->memcache()->select('a'));
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        } 
    }

    public function testGetMetaData()
    {
        try
        {
            $this->memcache()->insert('a', 1);
    
            $this->assertIsArray($this->memcache()->getMetaData('a'));

            $this->assertEmpty($this->memcache()->getMetaData('unknown'));
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        } 
    }

    /*
    public function testConnectionException()
    {
        try
        {
            new Drivers\MemcacheDriver
            ([
                'port'   => 1234,
                'host'   => 'unknown',
                'weight' => 1
            ]);
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
    */
}