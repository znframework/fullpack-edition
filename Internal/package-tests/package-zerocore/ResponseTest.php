<?php namespace ZN;

class ResponseTest extends ZerocoreExtends
{
    public function testRedirectInvalidRequest()
    {
        $this->assertNull(Response::redirectInvalidRequest());

        Config::routing('requestMethods', ['page' => 'redirect/page']);

        $this->assertNull(Response::redirectInvalidRequest());

        Config::routing('requestMethods', ['page' => '']);
    }

    public function testRedirect()
    {
        $this->assertNull(Response::redirect('Home/main', 1));
    }
}