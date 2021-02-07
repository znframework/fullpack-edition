<?php namespace ZN\Crontab;

class RemoveTest extends \PHPUnit\Framework\TestCase
{    
    public function testRemoveCronByID()
    {
        (new Job)->path('/opt/lampp/bin/php')->daily()->command('ExampleCommand:exampleMethod');

        (new Job)->remove(0);
        (new Job)->remove(0);

        $this->assertIsString((new Job)->listArray()[0] ?? '');
    }

    public function testRemoveCronByFilter()
    {
        (new Job)->command('ExampleCommand:exampleMethod');

        (new Job)->remove('ExampleCommand');

        $this->assertEmpty((new Job)->listArray());
    }

    public function testRemoveAll()
    {
        (new Job)->command('ExampleCommand:exampleMethod');

        (new Job)->remove();

        $this->assertEmpty((new Job)->listArray());
    }
}