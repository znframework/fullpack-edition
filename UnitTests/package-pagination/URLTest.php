<?php namespace ZN\Pagination;

use Pagination;

class URLTest extends \PHPUnit\Framework\TestCase
{
    public function testURL()
    {
        $this->assertStringContainsString('product/list/40', Pagination::url('product/list')->create());
    }
}