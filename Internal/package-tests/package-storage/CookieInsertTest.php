<?php namespace ZN\Storage;

use URL;
use Cookie;

class CookieInsertTest extends StorageExtends
{
    public function testInsert()
    {
        $this->insert('example', 'Example');

        $this->assertEquals('Example', Cookie::select('example'));
    }

    public function testOptionalMethods()
    {
        try
        {
            Cookie::path('cookie')
                ->time(60)
                ->domain(URL::site())
                ->secure(true)
                ->httpOnly(true)
                ->insert('example', 'Example');
        }
        catch( Exception\SetcookieException $e )
        {
            $this->assertEquals('Could not set the cookie!', $e->getMessage());
        }
    }

    public function testValueParameterWithArray()
    {
        try
        {
            Cookie::insert('example', ['Example']);
        }
        catch( Exception\SetcookieException $e )
        {
            $this->assertEquals('Could not set the cookie!', $e->getMessage());
        }
    }
}