<?php namespace ZN\Authentication;

use DB;
use Config;

class ForgotPasswordTest extends AuthenticationExtends
{
    public function testEmail()
    {
        (new ForgotPassword)->email('robot@znframework.com');

        $this->assertEquals('robot@znframework.com', Properties::$parameters['email']);
    }

    public function testVerification()
    {
        (new ForgotPassword)->verification('x89');

        $this->assertEquals('x89', Properties::$parameters['verification']);
    }

    public function testBefore()
    {
        (new ForgotPassword)->passwordChangeProcess('before');

        $this->assertEquals('before', Properties::$parameters['changePassword']);
    }

    public function testAfter()
    {
        (new ForgotPassword)->passwordChangeProcess('after');

        $this->assertEquals('after', Properties::$parameters['changePassword']);
    }

    public function testDoBefore()
    {
        Config::set('Auth', 
        [
            'encode'    => 'gost',
            'spectator' => '',
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
                    'verification' => 'verification',   
                    'otherLogin'   => ['phone']         
                ]
            ]
        ]);

        DB::where('username', 'robotBefore@znframework.com')->delete('users');

        (new Register)->do
        ([
            'username'     => 'robotBefore@znframework.com',
            'password'     => '1234',
            'verification' => 'x89'
        ]);

        (new ForgotPassword)->do('robotBefore@znframework.com', 'password/before', 'before');

        $this->assertEquals('Verification code or email information is wrong!', \User::error());

        $forgot = new ForgotPassword;

        $forgot->verification('x89');

        $forgot->do('robotBefore@znframework.com', 'password/before', 'before');

        $this->assertEquals('Your password has been sent to your email.', User::success());

        DB::where('username', 'robotBefore@znframework.com')->delete('users');
    }

    public function testForgotPasswordError()
    {
        DB::where('username', 'robotAfter@znframework.com')->delete('users');

        (new Register)->do
        ([
            'username'     => 'robotAfter@znframework.com',
            'password'     => '1234',
            'verification' => 'x89'
        ]);
       
        $forgot = new ForgotPassword;

        $forgot->verification('x89');

        $forgot->do('invalidEmail', 'password/after', 'after');

        $this->assertEquals('You are not registered on the system or your username is incorrect!', User::error());
      
        DB::where('username', 'robotAfter@znframework.com')->delete('users'); 
    }

    public function testDoAfter()
    {
        DB::where('username', 'robotAfter@znframework.com')->delete('users');

        (new Register)->do
        ([
            'username' => 'robotAfter@znframework.com',
            'password' => '1234',
            'verification' => 'x89'
        ]);
       
        $forgot = new ForgotPassword;

        $forgot->verification('x89');

        $forgot->do('robotAfter@znframework.com', 'password/after', 'after');

        $this->assertEquals('Your password has been sent to your email.', User::success());
      
        DB::where('username', 'robotAfter@znframework.com')->delete('users'); 
    }

    public function testPasswordChangeComplete()
    {
        (new ForgotPassword)->passwordChangeComplete();

        $this->assertEquals('You are not registered on the system or your username is incorrect!', \User::error());
    }
}