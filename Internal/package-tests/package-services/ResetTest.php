<?php namespace ZN\Services;

class ResetTest extends \PHPUnit\Framework\TestCase
{
    public function testResetReturnFalse()
    {
        $new = new CURL;

        $this->assertFalse($new->reset());
    }

    public function testResetReturnTrue()
    {
        $new = new CURL;

        $new->init()->exec();

        $this->assertTrue($new->reset());
    }
}