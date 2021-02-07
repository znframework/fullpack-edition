<?php namespace ZN\Cryptography;

class MappingTest extends \PHPUnit\Framework\TestCase
{
    public function testEncrypt()
    {
        $this->assertFalse((new CryptoMapping)->encrypt(NULL, NULL));
    }

    public function testDecrypt()
    {
        $this->assertFalse((new CryptoMapping)->decrypt(NULL, NULL));
    }

    public function testKeygen()
    {
        $this->assertFalse((new CryptoMapping)->keygen(NULL, NULL));
    }
}