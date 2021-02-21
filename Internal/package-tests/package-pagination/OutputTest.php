<?php namespace ZN\Pagination;

use URL;
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

    public function testOutputClassic()
    {
        $this->assertStringContainsString
        (
            'Home/main/190">last', 
            Pagination::output('classic')->linkNames('prev', 'next', 'first', 'last')->limit(10)->countLinks(5)->totalRows(200)->create(100)
        );
    }
}