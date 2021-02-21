<?php namespace ZN\Authentication;

use DB;
use User;
use Config;

class ActivationCompleteTest extends AuthenticationExtends
{
    public function testActivationCompleteFirstParameterException()
    {
        try
        {
            User::activationComplete([]);
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertStringContainsString('contains invalid information', $e->getMessage());
        }   
    }

    public function testActivationCompleteSecondParameterException()
    {
        try
        {
            User::activationComplete('user', []);
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertStringContainsString('contains invalid information', $e->getMessage());
        }   
    }

    public function testActivationCompleteEmptyParameterError()
    {
        User::activationComplete('', '');

        $this->assertEquals('The activation process could not be completed!', User::error());
    }

    public function testActivationCompleteInvalidInformationError()
    {
        User::activationComplete('abc', 'xyz');

        $this->assertEquals('The activation process could not be completed!', User::error());
    }

    public function testActivationCompleteSuccess()
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

        DB::where('username', 'robot@znframework.com')->delete('users');

        try
        {
            User::register
            ([
                'username' => 'robot@znframework.com',
                'password' => '1234'

            ], false, 'return/link');
        }
        catch( \Exception $e )
        {
            $this->assertStringContainsString('is an invalid email address!', $e->getMessage());
        }

        User::activationComplete('robot@znframework.com', User::getEncryptionPassword('1234'));

        $this->assertEquals('Your registration was completed successfully.', User::success());

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
}