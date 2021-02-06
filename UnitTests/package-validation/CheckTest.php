<?php namespace ZN\Validation;

class CheckTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        \Form::placeholder('Subject')->validate(['minchar' => 5, 'maxchar' => 250])->textarea('subject');

        \Post::subject('abcde');
        \Post::ValidationFormName('exampleForm');

        $data = new Data;

        $this->assertIsBool($data->check());
    }

    public function testInvalid()
    {
        unset($_POST);

        $data = new Data;

        $this->assertFalse($data->check());
    }

    public function testRuleCheckError()
    {
        \Post::example(1);
        	
        \Session::insert('FormValidationRules', []);

        $data = new Data;

        $this->assertFalse($data->check());

        $this->assertEquals('Rule check error!', $data->error('string'));
    }
}