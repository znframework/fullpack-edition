<?php namespace ZN\Email;

class MailDriverTest extends EmailExtends
{
    public function testSend()
    {
        $driver = new Drivers\MailDriver;  
        
        $driver->send('to@mail.com', 'Subject', 'Message', '');
    }   
}