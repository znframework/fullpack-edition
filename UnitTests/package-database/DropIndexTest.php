<?php namespace ZN\Database;

use DBForge;

class DropIndexTest extends DatabaseExtends
{
    public function testDropIndex()
    {
        DBForge::dropIndex('departmentCI', 'departments', 'description');

        $this->assertSame("DROP INDEX departmentCI;", trim(DBForge::stringQuery()));
    }
}