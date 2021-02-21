<?php namespace ZN\Helpers;

use Rounder;

class RounderTest extends \PHPUnit\Framework\TestCase
{
    public function testUp()
    {
        $this->assertSame(2.0, Rounder::up(1.5));
        $this->assertSame(1.6, Rounder::up(1.5, 2));
        $this->assertSame(1.524, Rounder::up(1.523454321, 3));

        try
        {
            Rounder::up(1, 3);
        }
        catch( Exception\LogicException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testDown()
    {
        $this->assertSame(1.0, Rounder::down(1.5));
        $this->assertSame(1.5, Rounder::down(1.5, 2));
        $this->assertSame(1.523, Rounder::down(1.523454321, 3));

        try
        {
            Rounder::down(1, 3);
        }
        catch( Exception\LogicException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testAverage()
    {
        $this->assertSame(2.0, Rounder::average(1.5));
        $this->assertSame(1.0, Rounder::average(1.4));
        $this->assertSame(2.0, Rounder::average(1.54));
    }
}