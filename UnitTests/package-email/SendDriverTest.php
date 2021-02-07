<?php namespace ZN\Email;

class SendDriverTest extends EmailExtends
{
    public function testSend()
    {
        $driver = new Drivers\SendDriver;  
        
        $driver->send('to@mail.com', 'Subject', 'Message', '');
    }   
}