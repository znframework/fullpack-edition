<?php namespace ZN;

class HtaccessTest extends ZerocoreExtends
{
    public function testCreate()
    {
        define('HTACCESS_CONFIG', Config::htaccess());
        
        Htaccess::create();
    }
}