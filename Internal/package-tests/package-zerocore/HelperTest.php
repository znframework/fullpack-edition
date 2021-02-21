<?php namespace ZN;

use Folder;

class HelperTest extends ZerocoreExtends
{
    public function testReport()
    {
        Config::project('log', ['createFile' => false]);

        $this->assertFalse(Helper::report('subject', 'message'));

        Config::project('log', ['createFile' => true]);

        Folder::delete(STORAGE_DIR . 'Logs/');

        Helper::report('subject', 'message');

        # Append
        Helper::report('subject', 'message');
    }

    public function testToConstant()
    {
        $this->assertEquals(PHP_VERSION, Helper::toConstant('PHP_VERSION', '_VERSION'));

        $this->assertEquals(PHP_VERSION, Helper::toConstant(PHP_VERSION, '_VERSION'));

        $this->assertEquals(CURRENT_PROJECT, Helper::toConstant(CURRENT_PROJECT, '_VERSION'));
    }
}