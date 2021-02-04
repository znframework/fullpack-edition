<?php namespace ZN\Pagination;

use Pagination;

class OutputTest extends \PHPUnit\Framework\TestCase
{
    public function testOutput()
    {
        $this->assertStringContainsString
        (
            '<ul class="pagination">', 
            Pagination::output('bootstrap')
               ->limit(15)
               ->totalRows(75)
               ->countLinks(5)
               ->create()
        );
    }
}