<?php namespace ZN\Validation;

use Post;
use Session;

class CheckTest extends \ZN\Test\GlobalExtends
{
    public function testInvalid()
    {
        unset($_POST);

        $data = new Data;

        $this->assertFalse($data->check());
    }

    public function testRuleCheckError()
    {
        Post::example(1);
        	
        Session::insert('FormValidationRules', []);

        $data = new Data;

        $this->assertFalse($data->check());

        $this->assertEquals('Rule check error!', $data->error('string'));
    }
}