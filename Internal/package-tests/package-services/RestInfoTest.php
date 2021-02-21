<?php namespace ZN\Services;

use Restful;

class RestInfoTest extends \PHPUnit\Framework\TestCase
{
    public function testGetCallInfo()
    {
        Restful::get('https://repo.packagist.org/p/znframework/znframework.json');

        $this->assertIsInt(Restful::gethttpcode());
    }
    
    public function testGetRequestHeaders()
    {
        if( function_exists('apache_request_headers') )
        {
            Restful::getRequestHeaders();
        }
    }
}