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

        $this->assertSame("CREATE TABLE persons(id   INTEGER  PRIMARY KEY  AUTOINCREMENT, name   VARCHAR(255)  NULL, address   TEXT) CHARACTER SET utf8 COLLATE utf8_general_ci ;", trim($this->persons->stringQuery()));
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
        $this->persons->update
        ([
            'name'    => 'ZERONEED',
            'address' => 'Istanbul/Turkey'
        ], 'id', 5);

        $this->assertSame("UPDATE persons SET name='ZERONEED',address='Istanbul/Turkey' WHERE id =  '5'", trim($this->persons->stringQuery()));
    }

    public function testGrandDeleteColumnName()
    {
        $this->persons->deleteId(1);

        $this->assertSame("DELETE  FROM persons WHERE Id =  '1'", trim($this->persons->stringQuery()));
    }

    public function testGrandDelete()
    {
        $this->persons->delete('id', 5);

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

    public function testGrandIsExists()
    {
        $this->assertIsBool($this->persons->isExists('name', 'Hulk'));
    }

    public function testGrandInsertCSV()
    {
        $this->assertIsBool($this->persons->insertCSV(self::default . 'package-database/resources/test.csv'));
    }

    public function testGrandColumns()
    {
        $this->assertIsArray($this->persons->columns());
    }

    public function testGrandTotalColumns()
    {
        $this->assertIsInt($this->persons->totalColumns());
    }

    public function testGrandIncrement()
    {
        $this->assertIsBool($this->persons->increment('surname'));
    }

    public function testGrandDecrement()
    {
        $this->assertIsBool($this->persons->decrement('surname'));
    }

    public function testGrandStatus()
    {
        $this->assertIsBool($this->persons->status());
    }
    
    public function testTruncate()
    {
        $this->assertIsBool($this->persons->truncate());
    }

    public function testOptimize()
    {
        $this->persons->optimize();
    }

    public function testRepair()
    {
        $this->persons->repair();
    }

    public function testBackup()
    {
        $this->persons->backup('example', self::default . 'package-database/resources/');
    }

    public function testError()
    {
        $this->persons->error();
    }

    public function testGetDatabaseConnections()
    {
        $this->persons->mockGetDatabaseConnections();
    }

    public function testSetGrandTableName()
    {
        $this->persons->mockSetGrandTableName();
    }

    public function testGetGrandTableName()
    {
        $this->persons->mockGetGrandTableName();
    }
}