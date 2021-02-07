<?php namespace ZN\Cryptography;

use Config;
use Encode;

class GoldenTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateGoldenKey()
    {
        $xkey = Encode::golden('Example Data', 'xkey');
        $ykey = Encode::golden('Example Data', 'ykey');

        $this->assertSame('c5c386872f7cdeabd560a0bb331d1ab7', $xkey);
        $this->assertSame('396e449bbf9ddc2929174dd105bcec23', $ykey);
    }

    public function testGoldenInvalidHash()
    {
        Config::cryptography('type', 'invalidhash');

        $golden = new \ZN\Cryptography\Encode\GoldenAlgorithm;

        $xkey = $golden->create('Example Data', 'xkey');

        $this->assertSame('c5c386872f7cdeabd560a0bb331d1ab7', $xkey);

        Config::cryptography('type', 'md5');
    }
}