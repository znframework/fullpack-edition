<?php namespace ZN\Response;

use Redirect;

class ActionTest extends \PHPUnit\Framework\TestCase
{
    public function testAction()
    {
        Redirect::exit(false)
                ->time(0) # same wait
                ->wait(0)
                ->data(['example' => 'Example']) # same insert
                ->insert(['example' => 'Example'])
                ->action('profile');
    }
}