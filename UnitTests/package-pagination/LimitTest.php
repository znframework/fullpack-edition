<?php namespace ZN\Pagination;

use URL;
use Pagination;

class LimitTest extends \PHPUnit\Framework\TestCase
{
    public function testLimit()
    {
        $this->assertStringContainsString
        (
            '<a href="' . URL::site('Home/main/45') . '" class="page-link">10</a>', 
            Pagination::limit(5)->create()
        );
    }
}