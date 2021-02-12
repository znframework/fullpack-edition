<?php namespace ZN;

use File;

class HtaccessTest extends ZerocoreExtends
{
    public function testCreate()
    {
        define('HTACCESS_CONFIG', Config::htaccess());

        $this->assertFalse(Htaccess::create());
        
        File::delete('.htaccess');

        $this->assertTrue(Htaccess::create());
    }
}