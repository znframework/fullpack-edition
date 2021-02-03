<?php namespace ZN\Shopping;

class MoneyFormatTest extends \PHPUnit\Framework\TestCase
{
    public function testFormat()
    {
        $shopping = new Money;

        $this->assertEquals('1.000,00', $shopping->format(1000));
    }

    public function testNumber()
    {
        $shopping = new Money;

        $this->assertEquals(1000, $shopping->number('1.000,00'));
    }
}