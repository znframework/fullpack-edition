<?php namespace ZN\Database;

use DB;
use DBForge;

class RenameColumnTest extends DatabaseExtends
{
    public function testRenameColumn()
    {
        DBForge::renameColumn('ExapleTable', ['phone mobile_phone' => [DB::int(), DB::notNull()]]);
        
        $this->assertSame("ALTER TABLE ExapleTable RENAME COLUMN phone mobile_phone TO  INTEGER   NOT NULL ;", trim(DBForge::stringQuery()));
    }
}