<?php namespace ZN\Validation;

class MessagesTest extends \ZN\Test\GlobalExtends
{
    public function testMessage()
    {
        \Post::data('123123');

        $data = new Data;

        $data->messages
        ([
            'cvc' => 'Invalid CVC!',
        ]);

        $data->value('XYZ')->cvc('maestro')->rules('data');

        $this->assertStringContainsString('Invalid CVC!', $data->error('string')); 
    }

    public function testRulesValidReplacement()
    {
        \Post::data('123123');

        $data = new Data;

        $data->messages
        ([
            'minchar' => ':name - :p1 '
        ]);

        $data->value('XYZ')->rules('data', ['minchar' => 10]);

        $this->assertStringContainsString('XYZ - 10', $data->error('string')); 
    }
}