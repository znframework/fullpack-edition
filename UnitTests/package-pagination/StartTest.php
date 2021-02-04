<?php namespace ZN\Pagination;

use URL;
use Pagination;

class StartTest extends \PHPUnit\Framework\TestCase
{
    public function testStart()
    {
        $this->assertStringContainsString
        (
            '<li class="page-item active"><a href="' . URL::site('Home/main/20') . '" class="page-link">3</a></li>', 
            Pagination::url('Home/main')->start(20)->create()
        );
    }
}