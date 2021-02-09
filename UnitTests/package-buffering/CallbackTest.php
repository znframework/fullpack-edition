<?php namespace ZN\Buffering;

class CallbackTest extends \PHPUnit\Framework\TestCase
{
    public function testDo()
    {
        $callback = new Callback;

        $return = $callback->do(function($param)
        { 
            return $param; 

        }, [1]);

        $this->assertSame('1', $return);
    }

    public function testCode()
    {
        $callback = new Callback;

        $return = $callback->do('str_replace', ['a', 'x', 'a']);

        $this->assertSame('x', $return);
    }
}