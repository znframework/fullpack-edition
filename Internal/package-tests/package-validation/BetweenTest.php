<?php namespace ZN\Validation;

class BetweenTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $this->assertTrue(Validator::between(10, 9, 11));
        $this->assertTrue(Validator::betweenBoth(10, 10, 11));
        $this->assertTrue(Validator::betweenBoth(10, 11, 10));
    }  

    public function testInvalid()
    {
        $this->assertFalse(Validator::between(10, 10, 11));
        $this->assertFalse(Validator::betweenBoth(10, 12, 13));
    }  

    public function testRulesValid()
    {
        \Post::data(10);

        $data = new Data;

        $data->between(9, 11)->rules('data');

        $this->assertEmpty($data->error('string')); 
    }

    public function testRulesInvalid()
    {
        \Post::data(10);

        $data = new Data;

        $data->between(10, 11)->rules('data');

        $this->assertIsString($data->error('string')); 
    }

    public function testBothRulesValid()
    {
        \Post::data(10);

        $data = new Data;

        $data->betweenBoth(10, 11)->rules('data');

        $this->assertEmpty($data->error('string')); 
    }

    public function testBothRulesInvalid()
    {
        \Post::data(10);

        $data = new Data;

        $data->betweenBoth(12, 13)->rules('data');

        $this->assertIsString($data->error('string')); 
    }
}