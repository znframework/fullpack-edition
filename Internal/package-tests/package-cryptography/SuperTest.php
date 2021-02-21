<?php namespace ZN\Cryptography;

use Config;
use Encode;

class SuperTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateSuperKey()
    {
        $this->assertIsString(Encode::super('Example Data'));
    }

    public function testSuperInvalidHash()
    {
        Config::cryptography('type', 'invalidhash');
        Config::project('key', '');

        $super = new \ZN\Cryptography\Encode\SuperAlgorithm;

        $xkey = $super->create('Example Data');

        $this->assertIsString($xkey);

        Config::project('key', 'd2d80f03f5aafbfc4b7cf6cfbeefed3a6ed24c2a3ea92aea193fcd78d0edcd01ed24630d3b81cc91');
        Config::cryptography('type', 'md5');
    }
}