<?php namespace ZN\Crontab;

class CommandTest extends \PHPUnit\Framework\TestCase
{
    public function testRunCommandDaily()
    {
        (new Job)->daily()->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('0 0 * * *', (new Job)->list());

        (new Job)->remove('ExampleCommand');
    }

    public function testRunCommandWithDayAndClock()
    {
        (new Job)->day('monday')->clock('10:00')->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('00 10 * * 1', (new Job)->list());

        (new Job)->remove('ExampleCommand');
    }

    public function testRunCommandSendParametersPerminute()
    {
        (new Job)->perminute(5)->parameters('1', '2')->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('(new \Project\Commands\ExampleCommand)->exampleMethod("1","2")', (new Job)->list());

        (new Job)->remove('ExampleCommand');
    }

    public function testRunCommandSendPerhour()
    {
        (new Job)->perhour(2)->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('* */2 * * *', (new Job)->list());

        (new Job)->remove('ExampleCommand');
    }

    public function testRunCommandHourly()
    {
        (new Job)->hourly()->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('0 * * * *', (new Job)->list());

        (new Job)->remove('ExampleCommand');
    }

    public function testRunCommandMidnight()
    {
        (new Job)->midnight()->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('0 0 * * *', (new Job)->list());

        (new Job)->remove('ExampleCommand');
    }

    public function testRunCommandWeekly()
    {
        (new Job)->weekly()->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('0 0 * * 0', (new Job)->list());

        (new Job)->remove('ExampleCommand');
    }

    public function testRunCommandYearly()
    {
        (new Job)->yearly()->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('0 0 1 1 *', (new Job)->list());

        (new Job)->remove('ExampleCommand');
    }

    public function testRunCommandAnnualy()
    {
        (new Job)->annualy()->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('0 0 1 1 *', (new Job)->list());

        (new Job)->remove('ExampleCommand');
    }

    public function testRunCommandDayNumber()
    {
        (new Job)->dayNumber(3)->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('* * 3 * *', (new Job)->list());

        (new Job)->remove('ExampleCommand');
    }

    public function testRunCommandMonth()
    {
        (new Job)->month('january')->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('* * * 1 *', (new Job)->list());

        (new Job)->remove('ExampleCommand');
    }

    public function testRunCommandPerMonth()
    {
        (new Job)->permonth('january')->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('* * * */1 *', (new Job)->list());

        (new Job)->remove('ExampleCommand');
    }

    public function testRunCommandPerDay()
    {
        (new Job)->perday('sunday')->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('* * * * */0', (new Job)->list());

        (new Job)->remove('ExampleCommand');
    }

    public function testRunCommandInterval()
    {
        (new Job)->interval('* 1 * * *')->command('ExampleCommand:exampleMethod');

        $this->assertStringContainsString('* 1 * * *', (new Job)->list());

        (new Job)->remove('ExampleCommand');
    }

    public function testGetCrontabCommands()
    {
        $this->assertIsString((new Job)->getCrontabCommands());
    }

    public function testLastJob()
    {
        (new Job)->lastJob();
    }

    public function testChangeProjectWithDriver()
    {
        (new Job)->project('Frontend')->driver('exec')->interval('* 1 * * *')->command('ExampleCommand:exampleMethod');

        (new Job)->remove('ExampleCommand');
    }

    public function testRunCommandInvalidClock()
    {
        try
        {
            (new Job)->clock('01:60')->command('ExampleCommand:exampleMethod');
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }
}