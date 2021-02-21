<?php namespace ZN\Response;

use Refresh;
use Redirect;

class ActionTest extends \PHPUnit\Framework\TestCase
{
    public function testAction()
    {
        Redirect::exit(false)
                ->time(0) 
                ->insert(['example' => 'Example'])
                ->action('profile');
    }

    public function testActionWithRefreshClass()
    {
        Refresh::wait(0)
               ->data(['example' => 'Example'])
               ->action('profile');
               
    }
}