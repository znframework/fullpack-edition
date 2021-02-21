<?php namespace ZN\Pagination;

use URL;
use Pagination;

class LinkNamesTest extends \PHPUnit\Framework\TestCase
{
    public function testLinkNames()
    {
        $this->assertStringContainsString
        (
            '<a href="' . URL::site('Home/main/10') . '" class="page-link">[ next ]</a></li>', 
            Pagination::linkNames('[ prev ]', '[ next ]', '[+ first +]', '[+ last +]')->create()
        );
    }
}