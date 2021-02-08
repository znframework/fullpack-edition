<?php namespace ZN\Cache;

class MemcacheDriverTest extends CacheExtends
{
    public function testInsert()
    {
        try
        {
            $redis = $this->memcache();

            $redis->insert('example', 1);
    
            $this->assertEquals(1, $redis->select('example'));
        }
        catch( \Exception $e )
        {
            echo $e->getMessage();
        }      
    }

    public function testDelete()
    {
        try
        {
            $redis = $this->memcache();

            $redis->insert('example', 1);

            $redis->delete('example');
    
            $this->assertEmpty($redis->select('example'));
        }
        catch( \Exception $e )
        {
            echo $e->getMessage();
        }      
    }

    public function testDecrement()
    {
        try
        {
            $redis = $this->memcache();

            $redis->insert('a', 2);

            $redis->decrement('a');
    
            $this->assertEquals(1, $redis->select('a'));
        }
        catch( \Exception $e )
        {
            echo $e->getMessage();
        }   
    }

    public function testIncrement()
    {
        try
        {
            $redis = $this->memcache();

            $redis->insert('a', 1);

            $redis->increment('a');
    
            $this->assertEquals(2, $redis->select('a'));
        }
        catch( \Exception $e )
        {
            echo $e->getMessage();
        }   
    }

    public function testInfo()
    {
        try
        {
            $redis = $this->memcache();

            $redis->insert('a', 1);
    
            $redis->info();
        }
        catch( \Exception $e )
        {
            echo $e->getMessage();
        } 
    }

    public function testClean()
    {
        try
        {
            $redis = $this->memcache();

            $redis->insert('a', 1);
    
            $redis->clean();

            $this->assertEmpty($redis->select('a'));
        }
        catch( \Exception $e )
        {
            echo $e->getMessage();
        } 
    }

    public function testGetMetaData()
    {
        try
        {
            $redis = $this->memcache();

            $redis->insert('a', 1);
    
            $this->assertIsArray($redis->getMetaData('a'));
        }
        catch( \Exception $e )
        {
            echo $e->getMessage();
        } 
    }
}