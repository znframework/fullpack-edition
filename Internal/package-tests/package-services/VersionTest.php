<?php namespace ZN\Services;

class VersionTest extends \PHPUnit\Framework\TestCase
{
    public function testVersion()
    {
        $curl = new CURL;

        $this->assertIsArray($curl->version());
    }

    public function testVersionByKey()
    {
        $curl = new CURL;

        $this->assertIsInt($curl->version('version_number'));
    }
}