<?php namespace ZN\Cryptography;

use Encode;
use Validator;

class CreateTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateKey()
    {
        $this->assertSame(10, strlen(Encode::create(10)));
    }

    public function testCreateKeyOnlyAlpha()
    {
        $this->assertTrue(Validator::alpha(Encode::create(10, 'alpha')));
    }

    public function testCreateKeyOnlyNumeric()
    {
        $this->assertTrue(Validator::numeric(Encode::create(10, 'numeric')));
    }

    public function testCreateKeyOnlySpecial()
    {
        $this->assertTrue((bool) preg_match('/\W/', Encode::create(10, 'special')));
    }
}