<?php namespace ZN\Crontab;

class ControllerTest extends \PHPUnit\Framework\TestCase
{
    public function testRunControllerDaily()
    {
        (new Job)->daily()->controller('ExampleController/exampleMethod');

        $this->assertStringContainsString('0 0 * * *', (new Job)->list());

        (new Job)->remove('ExampleController');
    }

    public function testRunControllerWithDayAndClock()
    {
        (new Job)->day('monday')->clock('10:00')->controller('ExampleController/exampleMethod');

        $this->assertStringContainsString('00 10 * * 1', (new Job)->list());

        (new Job)->remove('ExampleController');
    }

    public function testRunControllerSendPerhour()
    {
        (new Job)->perhour(2)->controller('ExampleController/exampleMethod');

        $this->assertStringContainsString('* */2 * * *', (new Job)->list());

        (new Job)->remove('ExampleController');
    }

    public function testRunControllerSendParemetersPerhour()
    {
        (new Job)->perhour(2)->controller('ExampleController/exampleMethod3/a/b/c');

        $this->assertStringContainsString('* */2 * * *', (new Job)->list());

        (new Job)->remove('ExampleController');
    }
}