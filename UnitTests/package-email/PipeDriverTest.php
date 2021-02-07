<?php namespace ZN\Email;

class PipeDriverTest extends EmailExtends
{
    public function testSend()
    {
        $driver = new Drivers\PipeDriver;  
        
        try
        {
            $driver->send('to@mail.com', 'Subject', 'Message'); 
        }
        catch( Exception\IOException $e )
        {
            $this->assertIsString($e->getMessage());
        }
        
    }   
}