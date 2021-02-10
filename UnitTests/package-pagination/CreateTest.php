<?php namespace ZN\Pagination;

use URL;
use Pagination;

class CreateTest extends PaginationExtends
{
    public function testCreate()
    {
        $this->assertStringContainsString('Home/main/40', Pagination::create());
    }

    public function testLast()
    {
        $this->assertStringContainsString
        (
            '<a href="' . URL::site('Home/main/190') . '" class="page-link">20</a>', 
            Pagination::linkNames('prev', 'next', 'first', 'last')->limit(10)->countLinks(5)->totalRows(200)->create(190)
        );
    }

    public function testFirst()
    {
        $this->assertStringContainsString
        (
            '<a href="' . URL::site('Home/main/190') . '" class="page-link">last</a>', 
            Pagination::linkNames('prev', 'next', 'first', 'last')->limit(10)->countLinks(5)->totalRows(200)->create(0)
        );
    }

    public function testMiddle()
    {
        $this->assertStringContainsString
        (
            '<a href="' . URL::site('Home/main/0') . '" class="page-link">first</a>', 
            Pagination::linkNames('prev', 'next', 'first', 'last')->limit(10)->countLinks(5)->totalRows(200)->create(100)
        );
    }

    public function testExplodeRequestGetValue()
    {
        $this->assertEquals('?baz=baz', $this->mock->mockExplodeRequestGetValue());

        $_SERVER['REQUEST_URI'] = NULL;
    }

    public function testCreateBasicPaginationBar()
    {
        $this->assertStringContainsString('Home/main/90', $this->mock->mockCreateBasicPaginationBar(100));
    }
}