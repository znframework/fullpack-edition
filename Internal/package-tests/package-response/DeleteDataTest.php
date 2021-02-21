<?php namespace ZN\Response;

use Redirect;

class DeleteDataTest extends \PHPUnit\Framework\TestCase
{
    public function testDeleteData()
    {
        Redirect::location('profile', 0, ['example' => 'Data'], false);

        $this->assertTrue(Redirect::deleteData('example'));
    }

    public function testDeleteDataMultiple()
    {
        Redirect::location('profile', 0, ['example' => 'Data', 'example2' => 'Data2'], false);

        $this->assertTrue(Redirect::deleteData(['example', 'example2']));
    }

    public function testDeleteDataAliasMethod()
    {
        Redirect::location('profile', 0, ['example' => 'Data'], false);

        $this->assertTrue(Redirect::delete('example'));
    }
}