<?php namespace ZN;

use Buffer;

class OutputTest extends ZerocoreExtends
{
    public function testWrite()
    {
        $result = Buffer::callback(function()
        {
            Output::write([]);
        });

        $this->assertEquals('Not String!', $result);

        $result = Buffer::callback(function()
        {
            Output::write('Hello');
        });

        $this->assertEquals('Hello', $result);

        $result = Buffer::callback(function()
        {
            Output::write('Hello {a}', ['a' => 'ZN']);
        });

        $this->assertEquals('Hello ZN', $result);
    }

    public function testWriteLine()
    {
        $result = Buffer::callback(function()
        {
            Output::writeLine('Hello {a}', ['a' => 'ZN']);
        });

        $this->assertEquals('Hello ZN<br>', $result);
    }

    public function testDisplay()
    {
        $result = Buffer::callback(function()
        {
            Output::display([1]);
        });

        $this->assertStringContainsString('integer</span> <span style="color:green;">1</span>', $result);
        $this->assertStringContainsString('integer</span> <span style="color:green;">1</span>', Output::display([1], [], true));
    }
}