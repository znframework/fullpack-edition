<?php namespace ZN\Email;

class SMTPDriverTest extends EmailExtends
{
    public function testSend()
    {
        $driver = new Drivers\SmtpDriver;   
    }   
}