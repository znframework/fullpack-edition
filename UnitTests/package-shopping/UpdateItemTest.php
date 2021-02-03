<?php namespace ZN\Shopping;

use Cart;

class UpdateItemTest extends \PHPUnit\Framework\TestCase
{
    public function testUpdate()
    {
        $insert = Cart::insert
        ([
            'product'       => 'Banana',
            'price'         => '10',
            'quantity'      => 3,
            'serial-number' => '4432222345219'
        ]);

        Cart::update('Banana', ['price' => 20]);

        $this->assertEquals(60, Cart::totalPrices());

        Cart::delete('4432222345219');
    }

    public function testUpdateByArrayParameter()
    {
        $insert = Cart::insert
        ([
            'product'       => 'Banana',
            'price'         => '10',
            'quantity'      => 3,
            'serial-number' => '4432222345219'
        ]);

        Cart::update(['product' => 'Banana'], ['price' => 20]);

        $this->assertEquals(60, Cart::totalPrices());

        Cart::delete('4432222345219');
    }

    public function testUpdateAddedUndefinedKey()
    {
        $insert = Cart::insert
        ([
            'product'       => 'Banana',
            'price'         => '10',
            'quantity'      => 3,
            'serial-number' => '4432222345219'
        ]);

        Cart::update(['product' => 'Banana'], ['price' => 20, 'unknown' => '1234']);

        $this->assertEquals('1234', Cart::select(['unknown' => '1234'])->unknown);

        Cart::delete('4432222345219');
    }

    public function testEmptyReturnFalse()
    {
        Cart::deleteAll();

        $this->assertFalse(Cart::update('Banana', ['price' => 20]));
    }
}