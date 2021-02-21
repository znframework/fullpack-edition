<?php namespace ZN\Hypertext;

use Html;

class BreadcrumbTest extends HypertextExtends
{
    public function testSetURI()
    {
        $this->assertStringContainsString
        (
            '<nav aria-label="breadcrumb">', 
            (string) Html::breadcrumb('Products/Asus/Computer')
        );
    }

    public function testSetAutoURI()
    {
        $this->assertStringContainsString
        (
            '<nav aria-label="breadcrumb">', 
            (string) Html::breadcrumb(NULL, 2)
        );
    }

    public function testBreadcrumbOlList()
    {
        $this->assertEquals
        (
            '<nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item">Home</li></ol></nav>',
            (string) Html::breadcrumb('Home/' . CURRENT_COPEN_PAGE, 2)
        );
    }
}