<?php namespace ZN\Helpers;

use Config;
use Logger;

class LoggerTest extends \PHPUnit\Framework\TestCase
{
    public function testReport()
    {
        Config::project('log', 
        [
            'createFile' => true,
            'fileTime' => '30 day'
        ]);

        Logger::report('Example Subject', 'Example Message');

        $this->assertFileExists(STORAGE_DIR . 'Logs/Example-Subject.log');
    }

    public function testReportNotice()
    {
        Logger::notice('Notice Message');

        $this->assertFileExists(STORAGE_DIR . 'Logs/notice.log');
    }

    public function testReportEmergency()
    {
        Logger::emergency('emergency Message');

        $this->assertFileExists(STORAGE_DIR . 'Logs/emergency.log');
    }

    public function testReportAlert()
    {
        Logger::alert('alert Message');

        $this->assertFileExists(STORAGE_DIR . 'Logs/alert.log');
    }

    public function testReportError()
    {
        Logger::error('error Message');

        $this->assertFileExists(STORAGE_DIR . 'Logs/error.log');
    }

    public function testReportWarning()
    {
        Logger::warning('warning Message');

        $this->assertFileExists(STORAGE_DIR . 'Logs/warning.log');
    }

    public function testReportCritical()
    {
        Logger::critical('critical Message');

        $this->assertFileExists(STORAGE_DIR . 'Logs/critical.log');
    }

    public function testReportInfo()
    {
        Logger::info('info Message');

        $this->assertFileExists(STORAGE_DIR . 'Logs/info.log');
    }

    public function testReportDebug()
    {
        Logger::debug('debug Message');

        $this->assertFileExists(STORAGE_DIR . 'Logs/debug.log');
    }
}