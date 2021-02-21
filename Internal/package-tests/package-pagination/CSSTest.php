<?php namespace ZN\Pagination;

use URL;
use Pagination;

class CSSTest extends \PHPUnit\Framework\TestCase
{
    public function testCSS()
    {
        $this->assertStringContainsString
        (
            'Home/main/0', 
            Pagination::limit(15)
               ->totalRows(200)
               ->countLinks(3)
               ->linkNames('[ prev ]', '[ next ]', '[+ first +]', '[+ last +]')
               ->css(['links' => 'links', 'current' => 'current'])
               ->create()
        );
    }
}