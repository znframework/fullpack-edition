<?php namespace ZN;

class ConfigTest extends ZerocoreExtends
{
    public function testSet()
    {
        $this->assertFalse(Config::set('file', []));
    }

    public function testIniSet()
    {
        $this->assertFalse(Config::iniset(''));

        $this->assertFalse(Config::iniset('post_max_size', []));

        $this->assertNull(Config::iniset('post_max_size', '10M'));

        $this->assertNull(Config::iniset(['post_max_size' => '10M']));
    }

    public function testIniGet()
    {
        $this->assertIsString(Config::iniget('post_max_size'));
        $this->assertIsArray(Config::iniget(['post_max_size']));
    }
}