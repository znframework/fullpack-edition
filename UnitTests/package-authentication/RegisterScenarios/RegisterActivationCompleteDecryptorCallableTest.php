<?php namespace ZN\Authentication;

use DB;
use User;
use Config;
use DBForge;

class RegisterActivationCompleteDecryptorCallableTest extends AuthenticationExtends
{
    public function testMake()
    {
        (new Register)->activationComplete('robot@znframework.com', function()
        {
            return 'password';
        });

        $this->assertEquals('The activation process could not be completed!', User::error());
    }
}