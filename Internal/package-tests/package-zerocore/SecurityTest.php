<?php namespace ZN;

use Post;

class SecurityTest extends ZerocoreExtends
{
    public function testValidCSSRFToken()
    {
        $this->assertFalse(Security::validCSRFToken('token', 'get'));
    }

    public function testCSRFToken()
    {
        Post::token('unknown');

        $this->assertNull(Security::CSRFToken('redirect/page'));
    }
}