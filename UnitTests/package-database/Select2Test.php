<?php namespace ZN\Database;

use DB;
use Json;
use Config;

class Select2Test extends DatabaseExtends
{
    public function testTableNameResult()
    {
        $result = DB::personsResult();

        $this->assertIsString($result[0]->name);
    }
    
    public function testResultArray()
    {
        $result = DB::persons()->resultArray();

        $this->assertIsString($result[0]['name']);
    }

    public function testResultJson()
    {
        $result = DB::persons()->resultJson();

        $this->assertTrue(Json::check($result));

        $this->assertIsString(json_decode($result)[0]->name);
    }

    public function testJsonDecode()
    {
        DB::where('name', 'Micheal')->update('persons', 
        [
            'phone' => ['a' => '12345', 'b' => '22334']
        ]);
        
        $person = DB::where('name', 'Micheal')->jsonDecode('phone')->persons()->row();

        $this->assertSame('12345', $person->phone->a);
    }

    public function testSelectPersonSingleFirstRow()
    {
        $person = DB::persons()->row();

        $this->assertIsObject($person);
    }

    public function testSelectPersonSingleRowByIndex()
    {
        $person = DB::persons()->row(1);

        $this->assertIsObject($person);
    }

    public function testSelectPersonSingleRowByLastIndex()
    {
        $person = DB::persons()->row(-1);

        $this->assertIsObject($person);
    }

    public function testSelectPersonOnlyColumnValue()
    {
        $person = DB::select('name')->where('name', 'Micheal')->persons()->row(true);

        $this->assertSame('Micheal', $person);
    }

    public function testTableNameRow()
    {
        $row = DB::personsRow();

        $this->assertIsObject($row);
    }

    public function testGetColumnValue()
    {
        $name = DB::where('name', 'Micheal')->persons()->value('name');

        $this->assertSame('Micheal', $name);
    }

    public function testGetColumnValueWithoutSelect()
    {
        $firstColumnValue = DB::where('name', 'Micheal')->persons()->value();

        $this->assertIsString($firstColumnValue); # id
    }

    public function testSelectPersonTotalRows()
    {
        $totalRows = DB::limit(1)->persons()->totalRows();

        $this->assertSame(1, $totalRows);
    }
    
    public function testSelectPersonRealTotalRows()
    {
        $person = DB::limit(1)->persons();

        $this->assertGreaterThan($person->totalRows(), $person->totalRows(true));
    }

    public function testGetTotalColumns()
    {
        $totalColumns = DB::persons()->totalColumns();

        $this->assertIsInt($totalColumns);
    }

    public function testGetColumns()
    {
        $columns = DB::persons()->columns();

        $this->assertContains('name', $columns);
    }

    public function testGetColumnData()
    {
        $columns = DB::persons()->columnData();

        $this->assertArrayHasKey('name', $columns);
    }

    public function testGetTableName()
    {
        $person = DB::persons();

        $this->assertSame('persons', $person->tableName());
    }

    public function testIsExists()
    {
        $this->assertTrue(DB::isExists('persons', 'surname', 'Tony'));
        $this->assertFalse(DB::isExists('persons', 'name', 'Samanta'));
    }

    public function testDebugInfo()
    {
        print_r(DB::get('persons'));
    }

    public function testDifferentConnectionWithName()
    {
        Config::database('database', 
        [
            'differentConnection' => 
            [
                'myConnect' => DatabaseExtends::postgres
            ]
        ]);

        (new Connection)->differentConnection('myConnect');
    }

    public function testDifferentConnectionInvalidParameterException()
    {
        try
        {
            DB::differentConnection('example');
        }
        catch( \Exception $e )
        {
            $this->assertEquals('`Mixed $connectName` input information is invalid!', $e->getMessage());
        }
    }
}