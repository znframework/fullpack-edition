<?php namespace ZN\Pagination;

use URL;
use Pagination;

class StyleTest extends \PHPUnit\Framework\TestCase
{
    public function testStyle()
    {
        $this->assertStringContainsString
        (
            '<li class="page-item active" style="font-size:30px;"><a href="' . URL::site('Home/main/0') . '" class="page-link">1</a></li><li style="color:green;"><a href="' . URL::site('Home/main/15') . '" class="page-link">2</a></li>', 
            Pagination::limit(15)
               ->totalRows(200)
               ->countLinks(3)
               ->linkNames('[ prev ]', '[ next ]', '[+ first +]', '[+ last +]')
               ->style(['links' => 'color:green;', 'current' => 'font-size:30px;'])
               ->create()
        );
    }
}