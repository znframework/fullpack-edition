<?php namespace ZN;

class InformationTest extends ZerocoreExtends
{
    public function testError()
    {
        $this->informationMock->mockError('error');

        $this->assertEquals('error', $this->informationMock->error());

        $this->informationMock->mockError(['error1', 'error2']);

        $this->assertEquals('error1<br>error2', $this->informationMock->error());

        $this->informationMock->mockError(NULL);

        $this->assertFalse($this->informationMock->error());
    }

    public function testSuccess()
    {
        $this->informationMock->mockError(NULL);

        $this->informationMock->mockSuccess('success');

        $this->assertEquals('success', $this->informationMock->success());

        $this->informationMock->mockSuccess(['success1', 'success2']);

        $this->assertEquals('success1<br>success2', $this->informationMock->success());

        $this->informationMock->mockSuccess(NULL);

        $this->assertEquals('The operation completed successfully.', $this->informationMock->success());
        
        $this->informationMock->mockError('error');

        $this->assertFalse($this->informationMock->success());
    }

    public function testStatus()
    {
        $this->informationMock->mockSuccess('success');

        $this->assertEquals('success', $this->informationMock->status());

        $this->informationMock->mockError('error');

        $this->assertEquals('error', $this->informationMock->status());
    }
}