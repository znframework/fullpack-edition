<?php namespace ZN\Authentication;

use DB;
use User;

class SendEmailTest extends AuthenticationExtends
{
    public function testSendAll()
    {
        \Config::set('Auth', 
        [
            'matching'  =>
            [
                'table'   => 'users',
                'columns' =>
                [
                    'username'     => 'username',
                    'password'     => 'password', 
                    'email'        => '',              
                    'active'       => '',      
                    'banned'       => '',       
                    'activation'   => '',     
                    'verification' => '',   
                    'otherLogin'   => []         
                ]
            ],
            'emailSenderInfo' =>
            [
                'name' => 'Robot',
                'mail' => 'robot@znframework.com'
            ]
        ]);

        DB::where('username', 'robot@znframework.com')->delete('users');

        (new Register)->do
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234'
        ]);

        $send = new SendEmail;

        $send->attachment('robots.txt');

        $send->send('New Topic', 'Added new topic');
    }
}