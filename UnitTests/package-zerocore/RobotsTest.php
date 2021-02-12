<?php namespace ZN;

use File;

class RobotsTest extends ZerocoreExtends
{
    public function testCreate()
    {
        $this->assertFalse(Robots::create());
    }

    public function testRecreate()
    {
        File::delete('robots.txt');

        $this->assertTrue(Robots::create());
    }

    public function testMulticreate()
    {
        File::delete('robots.txt');

        Config::set('Robots', 
        [
            'rules' => 
            [
                'userAgent' => '*',
                'allow'     => [],
                'disallow'  =>
                [
                    '/External/',
                    '/Internal/',
                    '/Projects/',
                    '/Settings/'
                ]
            ]
        ]);

        $this->assertTrue(Robots::create());
    }
}