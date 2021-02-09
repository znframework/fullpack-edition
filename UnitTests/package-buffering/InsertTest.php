<?php namespace ZN\Buffering;

class InsertTest extends \PHPUnit\Framework\TestCase
{
    public function testDo()
    {
        $insert = new Insert;

        $this->assertTrue($insert->do('a', 1));
    }

    public function testDoCallable()
    {
        $insert = new Insert;

        $this->assertTrue($insert->do('a', function()
        {
            return 1;
        }));
    }

    public function testDoFile()
    {
        $insert = new Insert;

        $this->assertTrue($insert->do('a', 'robots.txt'));
    }
}