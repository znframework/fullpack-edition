<?php namespace ZN;

class CallControllerTest extends ZerocoreExtends
{
    public function testCall()
    {
        try
        {
            $this->callControllerMock->invalid();
        }
        catch( \Exception $e )
        {
            $this->assertStringContainsString('::invalid()', $e->getMessage());
        } 
    }
}