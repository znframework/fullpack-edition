<?php namespace ZN\Email;

use Config;

class ImapDriverTest extends EmailExtends
{
    public function testSend()
    {
        try
        {
            $driver = new Drivers\ImapDriver; 
            
            $driver->send('to@mail.com', 'Subject', 'Message'); 
        }
        catch( Exception\IMAPConnectException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }   
}