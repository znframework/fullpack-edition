<?php namespace ZN\Pagination;

use URL;
use Pagination;

class CountLinksTest extends \PHPUnit\Framework\TestCase
{
    public function testCountLinks()
    {
        $this->assertStringContainsString
        (
            '<a href="' . URL::site('Home/main/10') . '" class="page-link">2</a>', 
            Pagination::countLinks(2)->limit(10)->totalRows(100)->create()
        );
    }

}