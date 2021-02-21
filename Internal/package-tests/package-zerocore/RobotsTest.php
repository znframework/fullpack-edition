<?php namespace ZN;

use File;

class RobotsTest extends ZerocoreExtends
{
    public function testMulticreate()
    {
        File::delete('robots.txt');
        
        Config::set('Robots', 
        [
            'createFile' => true,
            'rules' => 
            [
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
            ]
        ]);
        
        $this->assertTrue(Robots::create());
    }

    public function testRecreate()
    {
        File::delete('robots.txt');

        $this->assertTrue(Robots::create());
    }

    public function testCreate()
    {
        $this->assertFalse(Robots::create());
    }
}