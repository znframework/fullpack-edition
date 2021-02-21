<?php namespace ZN\Database;

use DB;

class DeleteTest extends DatabaseExtends
{
    public function testDelete()
    {
        DB::insert('persons', ['name' => 'Ozan']);

        DB::where('name', 'Ozan')->delete('persons');

        $this->assertFalse(DB::isExists('persons', 'name', 'Ozan'));
    }

    public function testDeleteUnconditionalException()
    {
        try
        {
            DB::delete('persons');
        }
        catch( Exception\UnconditionalException $exception )
        {
            $this->assertStringStartsWith('You can not perform unconditional deletion!', $exception->getMessage());
        }
    }

    public function testQuick()
    {
        DB::where('id', 1)->quick()->delete('example');

        $this->assertEquals("DELETE  QUICK  FROM example WHERE id =  '1' ", DB::stringQuery());
    }

    public function testDeleteWithJoin()
    {
        DB::leftJoin('abc.id', 'xyz.id')->where('xyz.id', 1)->delete('xyz');

        $this->assertEquals("DELETE  xyz  FROM xyz LEFT JOIN abc ON abc.id = xyz.id   WHERE xyz.id =  '1' ", DB::stringQuery());
    }
}