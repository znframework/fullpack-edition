<?php namespace ZN;

class SingletonTest extends ZerocoreExtends
{
    public function testCreateNewInstance()
    {
        try
        {
            Singleton::class('Example');
        }
        catch( \Throwable $e )
        {
            echo $e->getMessage();
        }
    }
}