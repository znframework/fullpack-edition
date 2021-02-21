<?php namespace ZN\Validation;

class MethodTest extends \ZN\Test\GlobalExtends
{
    public function testGet()
    {
        \Get::data('123123');

        $data = new Data;

        $data->method('get')->cvc('maestro')->rules('data');

        $this->assertIsString($data->error('string')); 
    }

    public function testData()
    {
        $data = new Data;

        $data->method('data')->cvc('maestro')->rules('123123');

        $this->assertIsString($data->error('string')); 
    }
}