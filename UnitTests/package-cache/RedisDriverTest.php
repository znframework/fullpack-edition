<?php namespace ZN\Cache;

class RedisDriverTest extends CacheExtends
{
    public function testInsert()
    {
        try
        {
            $redis = $this->redis();

            $redis->insert('example', 1);
    
            $this->assertEquals(1, $redis->select('example'));
        }
        catch( \Exception $e )
        {
            echo $e->getMessage();
        }      
    }
}