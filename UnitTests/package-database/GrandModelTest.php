<?php namespace ZN\Database;

use DB;

class GrandModelTest extends DatabaseExtends
{
    public function testGrandCreate()
    {
        $this->persons->id([DB::int(11), DB::primaryKey(), DB::autoIncrement()])
               ->name([DB::varchar(255), DB::null()])
               ->address([DB::text()])
               ->create(DB::encoding());

        $this->assertSame("CREATE TABLE persons(id   INTEGER(11)  PRIMARY KEY  AUTOINCREMENT, name   VARCHAR(255)  NULL, address   TEXT) CHARACTER SET utf8 COLLATE utf8_general_ci ;", trim($this->persons->stringQuery()));
    }

    public function testGrandInsert()
    {
        $this->persons->name('ZN Framework')->address('Istanbul')->insert();

        $this->assertSame("INSERT  INTO persons (name,address) VALUES ('ZN Framework','Istanbul')", trim($this->persons->stringQuery()));
    }

    public function testGrandInsertMatch()
    {
        $this->persons->insert('post');

        $this->assertStringStartsWith("INSERT  INTO persons", trim($this->persons->stringQuery()));
    }

    public function testGrandInsertDuplicateCheck()
    {
        $this->persons->duplicateCheck()->name('ZN Framework')->address('Istanbul')->insert();

        $this->assertSame("INSERT  INTO persons (name,address) VALUES ('ZN Framework','Istanbul')", trim($this->persons->stringQuery()));
    }

    public function testGrandInsertDuplicateCheckUpdate()
    {
        $this->persons->duplicateCheck()->name('ZN Framework')->address('Istanbul')->insert();

        $this->assertSame("INSERT  INTO persons (name,address) VALUES ('ZN Framework','Istanbul')", trim($this->persons->stringQuery()));
    }

    public function testGrandInsertId()
    {
        $this->persons->name('ZN Framework')->phone('1234')->insert();

        $this->assertIsInt($this->persons->insertId());
    }

    public function testGrandTotalRows()
    {
        $this->persons->limit(1)->result();

        $this->assertIsInt($this->persons->totalRows());
    }

    public function testGrandResultWithSelect()
    {
        $this->persons->select('name', 'surname')->result();

        $this->assertSame("SELECT  name,surname  FROM persons", trim($this->persons->stringQuery()));
    }

    public function testGrandUpdateColumnName()
    {
        $this->persons->updateId
        ([
            'name'    => 'ZERONEED',
            'address' => 'Istanbul/Turkey'
        ], 1);

        $this->assertSame("UPDATE persons SET name='ZERONEED',address='Istanbul/Turkey' WHERE Id =  '1'", trim($this->persons->stringQuery()));
    }

    public function testGrandUpdate()
    {
        $this->persons->where('id', 5)->update
        ([
            'name'    => 'ZERONEED',
            'address' => 'Istanbul/Turkey'
        ]);

        $this->assertSame("UPDATE persons SET name='ZERONEED',address='Istanbul/Turkey' WHERE id =  '5'", trim($this->persons->stringQuery()));
    }

    public function testGrandDeleteColumnName()
    {
        $this->persons->deleteId(1);

        $this->assertSame("DELETE  FROM persons WHERE Id =  '1'", trim($this->persons->stringQuery()));
    }

    public function testGrandDelete()
    {
        $this->persons->where('id', 5)->delete();

        $this->assertSame("DELETE  FROM persons WHERE id =  '5'", trim($this->persons->stringQuery()));
    }

    public function testGrandRowColumnName()
    {
        $this->persons->rowId(1);

        $this->assertSame("SELECT  *  FROM persons  WHERE Id =  '1'", trim($this->persons->stringQuery()));
    }

    public function testGrandResultColumnName()
    {
        $this->persons->resultId(1);

        $this->assertSame("SELECT  *  FROM persons  WHERE Id =  '1'", trim($this->persons->stringQuery()));
    }

    public function testGrandResult()
    {
        $this->persons->result();

        $this->assertSame("SELECT  *  FROM persons", trim($this->persons->stringQuery()));
    }

    public function testGrandPagination()
    {
        $this->persons->limit(NULL, 5)->result();

        $this->assertIsString($this->persons->pagination());
    }

    public function testGrandAddColumn()
    {
        $this->persons->address([DB::text()])->date([DB::datetime()])->add();

        $this->assertSame("ALTER TABLE persons ADD address  TEXT ,date  DATETIME ;", trim($this->persons->stringQuery()));
    }

    public function testGrandModifyColumn()
    {
        $this->persons->address([DB::text()])->date([DB::datetime()])->modify();

        # SQLite3 unsupported
        $this->assertSame("", trim($this->persons->stringQuery()));
    }

    public function testGrandRenameColumn()
    {
        $this->persons->address(['address2', DB::text()])->rename();

        # SQLite3 unsupported
        $this->assertSame("", trim($this->persons->stringQuery()));
    }

    public function testGrandDropColumn()
    {
        $this->persons->address(['address2', DB::text()])->drop();

        # SQLite3 unsupported
        $this->assertSame("", trim($this->persons->stringQuery()));
    }

    public function testGrandGet()
    {
        $table = $this->persons->get();

        # SQLite3 unsupported
        $this->assertSame("SELECT  *  FROM persons", trim($table->stringQuery()));
    }
}