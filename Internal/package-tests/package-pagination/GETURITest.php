<?php namespace ZN\Pagination;

use Pagination;

class GETURITest extends \PHPUnit\Framework\TestCase
{
    public function testGetURI()
    {
        $this->assertEquals('Home/main/', Pagination::getURI());
    }

    public function testGetURIWithFirstParameter()
    {
        $this->assertEquals('Home/main/bar?example=1', Pagination::getURI('bar?example=1'));
    }
}