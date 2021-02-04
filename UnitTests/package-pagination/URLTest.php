<?php namespace ZN\Pagination;

use Pagination;

class URLTest extends \PHPUnit\Framework\TestCase
{
    public function testURL()
    {
        $this->assertStringContainsString('product/list/40', Pagination::url('product/list')->create());
    }

    public function testURLQuery()
    {
        $this->assertStringContainsString('product/list/40', Pagination::url('product/list?example=1')->create());
    }
}