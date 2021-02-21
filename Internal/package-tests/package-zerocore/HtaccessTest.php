<?php namespace ZN;

use File;

class HtaccessTest extends ZerocoreExtends
{
    public function testCreate()
    {
        Config::htaccess('createFile', true);

        define('HTACCESS_CONFIG', Config::htaccess());

        Htaccess::create();
    }
}