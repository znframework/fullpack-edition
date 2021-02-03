<?php namespace ZN\Storage;

class ExceptionTest extends StorageExtends
{
    public function testSetcookieException()
    {
        try
        {
            throw new Exception\SetcookieException();
        }
        catch( Exception\SetcookieException $e )
        {
            $this->assertEquals('Could not set the cookie!', $e->getMessage());
        }
    }
}