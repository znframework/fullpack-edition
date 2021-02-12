<?php namespace ZN;

class SecurityTest extends ZerocoreExtends
{
    public function testCreateNewInstance()
    {
        $this->assertFalse(Security::validCSRFToken('token', 'get'));
    }
}