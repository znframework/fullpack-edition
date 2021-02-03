<?php namespace ZN\Authentication;

use DB;
use User;
use Config;

class ResendActivationMailTest extends \ZN\Test\GlobalExtends
{
    public function testActivationColumnNotSetError()
    {
        $register = new Register;

        try
        {
            $register->resendActivationEmail('robot@znframework.com', 'return/link');
        }
        catch( Exception\ActivationColumnException $e )
        {
            $this->assertEquals('Activation column not set!', $e->getMessage());
        }   
    }

    public function testActivationResendError()
    {
        Config::set('Auth', 
        [
            'matching'  =>
            [
                'table'   => 'users',
                'columns' =>
                [
                    'username'     => 'username',
                    'password'     => 'password', 
                    'email'        => '',              
                    'active'       => 'active',      
                    'banned'       => 'banned',       
                    'activation'   => 'activation',     
                    'verification' => '',   
                    'otherLogin'   => ['phone']         
                ]
            ]
        ]);
        
        $register = new Register;

        $register->resendActivationEmail('robotxx@znframework.com', 'return/link');

        $this->assertEquals('Activation code e-mail could not be sent if the specified e-mail address has already been activated!', User::error());

        Config::set('Auth', 
        [
            'matching'  =>
            [
                'table'   => 'users',
                'columns' =>
                [
                    'username'     => 'username',
                    'password'     => 'password', 
                    'email'        => '',              
                    'active'       => 'active',      
                    'banned'       => 'banned',       
                    'activation'   => '',     
                    'verification' => '',   
                    'otherLogin'   => ['phone']         
                ]
            ]
        ]);
    }

    public function testActivationResendSuccess()
    {
        DB::where('username', 'robot@znframework.com')->delete('users');

        User::register
        ([
            'username' => 'robot@znframework.com',
            'password' => '1234',
            'activation' => 0
        ]);

        Config::set('Auth', 
        [
            'matching'  =>
            [
                'table'   => 'users',
                'columns' =>
                [
                    'username'     => 'username',
                    'password'     => 'password', 
                    'email'        => '',              
                    'active'       => 'active',      
                    'banned'       => 'banned',       
                    'activation'   => 'activation',     
                    'verification' => '',   
                    'otherLogin'   => ['phone']         
                ]
            ],
            'emailSenderInfo' =>
            [
                'name' => 'Robot X',
                'mail' => 'Robot X'
            ]
        ]);

        $register = new Register;

        try
        {
            $register->resendActivationEmail('robot@znframework.com', 'return/link');
        }
        catch( \Exception $e )
        {
            $this->assertEquals('`Robot X` is an invalid email address!', $e->getMessage());
        }

        Config::set('Auth', 
        [
            'matching'  =>
            [
                'table'   => 'users',
                'columns' =>
                [
                    'username'     => 'username',
                    'password'     => 'password', 
                    'email'        => '',              
                    'active'       => 'active',      
                    'banned'       => 'banned',       
                    'activation'   => '',     
                    'verification' => '',   
                    'otherLogin'   => ['phone']         
                ],
                'emailSenderInfo' =>
                [
                    'name' => '',
                    'mail' => ''
                ]
            ]
        ]);
    }
}