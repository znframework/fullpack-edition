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
        DB::where('username', 'robotBefore@znframework.com')->delete('users');

        (new Register)->do
        ([
            'username'     => 'robotBefore@znframework.com',
            'password'     => '1234',
            'verification' => 'x89'
        ]);

        try
        {
            $return = (new ForgotPassword)->do('robotBefore@znframework.com', 'password/before', 'before');

            $this->assertEquals('Verification code or email information is wrong!', \User::error());

            $forgot = new ForgotPassword;

            $forgot->verification('x89');

            $return = $forgot->do('robotBefore@znframework.com', 'password/before', 'before');

            $this->assertEquals('Verification code or email information is wrong!', \User::error());

        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }

        DB::where('username', 'robotBefore@znframework.com')->delete('users');
    }

    public function testDoAfter()
    {
        DB::where('username', 'robotAfter@znframework.com')->delete('users');

        (new Register)->do
        ([
            'username' => 'robotAfter@znframework.com',
            'password' => '1234'
        ]);

        try
        {
            (new ForgotPassword)->do('robotAfter@znframework.com', 'password/after', 'after');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }

        DB::where('username', 'robotAfter@znframework.com')->delete('users'); 
    }
}