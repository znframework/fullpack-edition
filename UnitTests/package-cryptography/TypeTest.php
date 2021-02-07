<?php namespace ZN\Cryptography;

use Encode;

class TypeTest extends \PHPUnit\Framework\TestCase
{
    
    public function testCreateKeyByAlgo()
    {
        $this->assertSame('82855e89f2a92981d1f5578816579742', Encode::type('Example Data', 'md5'));
        $this->assertSame('d848a9d8d6e8844cd69b1724cc6bd4cab631f94ceb65c5ded7f3e98009c41fd9', Encode::type('Example Data', 'gost'));
    }

    public function testCreateInvalidHash()
    {
        try
        {
            Encode::type('abc', 'xyz');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testTypeWithGolden()
    {
        $this->assertIsString(Encode::type('abc', 'golden'));
    }
}