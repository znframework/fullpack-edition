<?php namespace ZN\Pagination;

use URL;
use Pagination;

class TotalRowsTest extends \PHPUnit\Framework\TestCase
{
    public function testTotalRows()
    {
        $this->assertStringContainsString
        (
            '<a href="' . URL::site('Home/main/40') . '" class="page-link">3</a>', 
            Pagination::limit(20)->totalRows(45)->create()
        );
    }
}