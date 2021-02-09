<?php namespace ZN\Email;

use DB;
use Email;
use Config;
use DBForge;

class OptionalMethodsTest extends EmailExtends
{
    public function testEnv()
    {
        print_r(\ZN\Console\Environment::export('SMTP_PASSWORD'));
    }

    public function testContentType()
    {
        Email::contentType('plain');
        Email::contentType('html');
    }   

    public function testCharset()
    {
        Email::charset('utf-8');
       
        try
        {
            Email::charset('xyz');
        }
        catch( Exception\InvalidCharsetException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testPriority()
    {
        Email::contentType(3);  # Valid
        Email::contentType(10); # Invalid returned 3
    }

    public function testAddHeader()
    {
        Email::addHeader('send', 'me');
    }

    public function testEncodingType()
    {
        Email::encodingType();
    }

    public function testMultipart()
    {
        Email::multipart();
    }

    public function testSmtpHost()
    {
        Email::smtpHost('mail.example.com');
    }

    public function testSmtpUser()
    {
        Email::smtpUser('user');
    }

    public function testSmtpPassword()
    {
        Email::smtpPassword('password');
    }

    public function testSmtpDsn()
    {
        Email::smtpDsn();
    }

    public function testSmtpTimeout()
    {
        Email::smtpTimeout();
    }

    public function testSmtpKeepAlive()
    {
        Email::smtpKeepAlive();
    }

    public function testSmtpEncode()
    {
        Email::smtpEncode('tls');
    }

    public function testTo()
    {
        Email::to('to@mail.com');
    }

    public function testReceiver()
    {
        Email::receiver('to@mail.com');
    }

    public function testReplyTo()
    {
        Email::replyTo('toreply@mail.com');
    }

    public function testCC()
    {
        Email::cc('tocc@mail.com');
    }

    public function testBCC()
    {
        Email::bcc('tobcc@mail.com');
    }

    public function testFrom()
    {
        Email::from('from@mail.com');
    }

    public function testSender()
    {
        Email::sender('sender@mail.com');
    }

    public function testSubject()
    {
        Email::subject('Mail Subject');
    }

    public function testMessage()
    {
        Email::message('Mail Message');
    }

    public function testContent()
    {
        Email::content('Mail Message');
    }

    public function testTemplate()
    {
        Config::database('database', \ZN\Database\DatabaseExtends::postgres);

        DBForge::createTable('templates', 
        [
            'name'    => DB::varchar(255),
            'content' => DB::text()
        ]);

        DB::insert('templates', 
        [
            'name'    => 'example',
            'content' => 'Hello {{name}}'
        ]);

        Config::database('database', $settings);

        Email::template('templates:content', 'name:example', 
        [
            'name'  => 'ZN'
        ]);

        DBForge::truncate('templates');

        Config::database('database', \ZN\Database\DatabaseExtends::sqlite);
    }

    public function testTemplateMatch()
    {
        $message = Email::templateMatch('Hello {{name}}',  
        [
            'name'  => 'ZN'
        ]);

        $this->assertEquals('Hello ZN', $message);
    }

    public function testAttachment()
    {
        Email::attachment(self::default . 'package-email/attachments/file.txt')
             ->attachment(self::default . 'package-email/attachments/icon.png')
             ->attachment(self::default . 'package-email/attachments/icon.pngx') # Invalid path
             ->attachment(self::default . 'package-email/attachments/icon.png', NULL, NULL, 'abc'); # Invalid Mime
    }

    public function testAttachmentContentId()
    {
        $this->assertIsString(Email::attachmentContentId(self::default . 'package-email/attachments/file.txt'));
        $this->assertFalse(Email::attachmentContentId(self::default . 'package-email/attachments/file.txtxx'));
    }
}