<?php namespace ZN\Validation;

class RulesTest extends \ZN\Test\GlobalExtends
{
    public function testUsageValidate()
    {
        \Post::data('1234');

        $data = new Data;

        $data->validate('numeric', 'trim')->rules('data');

        $this->assertEmpty($data->error('string')); 
    }

    public function testUsageSecure()
    {
        \Post::data('1234');

        $data = new Data;

        $data->secure('xss', 'html', 'script')->rules('data');

        $this->assertEmpty($data->error('string')); 
    }

    public function testCompare()
    {
        \Post::data('1234');

        $data = new Data;

        $data->compare(3, 10)->rules('data');

        $this->assertEmpty($data->error('string')); 
    }

    public function testRules()
    {
        \Get::data(10);

        $data = new Data;

        $data->rules('data', ['minchar' => 1, 'xss'], 'data', 'get');

        $this->assertEmpty($data->error('string')); 
    }

    public function testRulesMultiple()
    {
        \Get::data([10, 4, 5]);

        $data = new Data;

        $data->rules('data', ['minchar' => 1, 'xss'], 'data', 'get');

        $this->assertIsString($data->error('string'));
    }

    public function testRulesMultipleWithNames()
    {
        \Get::data([10, 4, 5]);

        $data = new Data;

        $data->rules('data', ['minchar' => 1, 'xss'], ['a', 'b', 'c'], 'get');

        $this->assertIsString($data->error('string'));
    }

    public function testRulesError()
    {
        \Post::data(1);

        $data = new Data;

        $data->rules('data', ['minchar' => 2, 'xss']);

        $this->assertIsString($data->error('string')); 
        $this->assertIsArray($data->error());
        $this->assertIsString($data->error('data'));
        $this->assertFalse($data->error('datax')); 
    }

    public function testInvalidArgumentException()
    {
        \Post::data(1);

        $data = new Data;

        try
        {
            $data->rules('data', ['minchar' => 2, 'xss'], 'abc', 'unknown');
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}