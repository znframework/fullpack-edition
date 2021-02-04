<?php namespace ZN\Pagination;

use Pagination;

class TypeTest extends \PHPUnit\Framework\TestCase
{
    public function testType()
    {
        $this->assertStringContainsString
        (
            '<ul class="pagination">', 
            Pagination::output('bootstrap')
               ->type('ajax')
               ->limit(15)
               ->totalRows(75)
               ->countLinks(5)
               ->create()
        );
    }
}