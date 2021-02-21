<?php namespace ZN\Services;

class CloseTest extends \PHPUnit\Framework\TestCase
{
    public function testClose()
    {
        $new = new CURL;

        $this->assertFalse($new->close());
    }
}