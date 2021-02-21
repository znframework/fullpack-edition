<?php namespace ZN;

class LangTest extends ZerocoreExtends
{
    public function testCall()
    {
        $this->assertIsArray(\Lang::error());
    }

    public function testSetByURI()
    {
        $_SERVER['PATH_INFO'] = 'Frontend/tr/Home/main';

        Lang::setByURI();

        $this->assertEquals('tr', Lang::get());

        Lang::set('en');
    }

    public function testSet()
    {
        $this->assertTrue(\Lang::set());
    }

    public function testGet()
    {
        $this->assertEquals('en', \Lang::get());
        $this->assertEquals('en', \Lang::get());
    }

    public function testGetDefault()
    {
        $this->assertIsArray(\Lang::default('ZN\CoreDefaultLanguage')::select('errors'));
        $this->assertFalse(\Lang::default((object)['a' => 1])::select('errorsy'));
        $this->assertFalse(\Lang::default(['a' => 1])::select('errorsy'));
    }

    public function testSelect()
    {
        $this->assertEquals('`%` table is not exists!', Lang::select('Database', 'tableNotExistsError', []));
    }
}