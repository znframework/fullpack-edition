<?php namespace ZN\Database;

use DB;
use Get;
use URI;
use Post;
use File;
use Json;
use Config;
use Buffer;
use Request;

class SelectTest extends DatabaseExtends
{
    public function testInsertPerson()
    {
        DB::duplicateCheck('name')->insert('persons', 
        [
            'name'    => 'Micheal',
            'surname' => 'Tony'
        ]);

        $totalRows = DB::where('name', 'Micheal')->persons()->totalRows();

        $this->assertSame(1, $totalRows);
    }

    public function testSelectPerson()
    {
        $row = DB::where('name', 'Micheal')->persons()->row();

        $this->assertSame('Micheal', $row->name);
    }

    public function testSelectPersonColumns()
    {
        $row = DB::select('name', 'surname')->persons()->row();

        $columns = array_keys((array) $row);

        $this->assertSame(['name', 'surname'], $columns);
    }

    public function testSelectPersonWithWhereEqualClause()
    {
        $row = DB::where('name', 'Micheal')->persons()->row();

        $this->assertSame('Micheal', $row->name);
    }

    public function testSelectPersonsWithWhereNotEqualClause()
    {
        $row = DB::where('name !=', 'Micheal')->persons()->row();

        $name = $row->name ?? '';

        $this->assertTrue('Micheal' != $name);
    }

    public function testSelectPersonWithWhereOtherOperatorClause()
    {
        $row = DB::where('phone >', '1000')->persons()->row();

        $this->assertIsObject($row);
    }

    public function testSelectPersonWithMultipleWhereAndClauses()
    {
        $row = DB::where('surname', 'Tony')->where('name', 'Micheal')->persons()->row();

        $this->assertSame('Micheal', $row->name);
    }

    public function testSelectPersonWithMultipleWhereOrClauses()
    {
        $row = DB::where('surname', 'Tony', 'or')->where('name', 'Micheal')->persons()->row();

        $this->assertSame('Micheal', $row->name);
    }

    public function testSelectPersonWithMultipleOneWhereClauses()
    {
        $row = DB::where
        ([
            ['surname', 'Tony', 'or'], 
            ['name', 'Micheal']
        ])->persons()->row();

        $this->assertSame('Micheal', $row->name);
    }

    public function testWhereColumnConvertToInt()
    {
        $normal = DB::string()->where('id', 1)->users();

        $this->assertSame("SELECT  *  FROM users  WHERE id =  '1' ", $normal);

        $toInt = DB::string()->where('int:id', 1)->users();

        $this->assertSame("SELECT  *  FROM users  WHERE id =  1 ", $toInt);
    }

    public function testWhereColumnConvertToFloat()
    {
        $normal = DB::string()->where('price >', '100.00')->prices();

        $this->assertSame("SELECT  *  FROM prices  WHERE price > '100.00' ", $normal);

        $toFloat = DB::string()->where('float:price >', '100.00')->prices();

        $this->assertSame("SELECT  *  FROM prices  WHERE price > 100 ", $toFloat);
    }

    public function testWhereColumnConvertToNonquotes()
    {
        $normal = DB::string()->where('price >', 'COUNT(price)')->prices();

        $this->assertSame("SELECT  *  FROM prices  WHERE price > 'COUNT(price)' ", $normal);

        $toExp = DB::string()->where('exp:price >', 'COUNT(price)')->prices();

        $this->assertSame("SELECT  *  FROM prices  WHERE price > COUNT(price) ", $toExp);
    }

    public function testWhereGroup()
    {
        $stringQuery = DB::string()->whereGroup
        (
            ['id', 1, 'or'],
            ['id', 2],
            'and'
        )
        ->whereGroup
        (
            ['name', 'ZN', 'and'],
            ['address', 'Istanbul']  
        )
        ->users(); 

        $this->assertSame("SELECT  *  FROM users  WHERE ( id =  '1' or  id =  '2'  ) and  ( name =  'ZN' and  address =  'Istanbul'  ) ", $stringQuery);
    }

    public function testHaving()
    {
        $stringQuery = DB::string()->having('price >', '100')->prices();

        $this->assertSame("SELECT  *  FROM prices  HAVING price > '100' ", $stringQuery);
    }

    public function testHavingGroup()
    {
        $stringQuery = DB::string()->havingGroup
        (
            ['id', 1, 'or'],
            ['id', 2],
            'and'
        )
        ->havingGroup
        (
            ['name', 'ZN', 'and'],
            ['address', 'Istanbul']  
        )
        ->users(); 

        $this->assertSame("SELECT  *  FROM users  HAVING ( id =  '1' or  id =  '2'  ) and  ( name =  'ZN' and  address =  'Istanbul'  ) ", $stringQuery);
    }

    public function testGroupBy()
    {
        $stringQuery = DB::string()->groupBy('name')->users();

        $this->assertSame('SELECT  *  FROM users  GROUP BY name', $stringQuery);
    }

    public function testOrderBy()
    {
        $stringQuery = DB::string()->orderBy('id', 'DESC')->where('id >', 10)->users();

        $this->assertSame("SELECT  *  FROM users  WHERE id > '10'  ORDER BY id DESC", $stringQuery);
    }

    public function testMultipleOrderBy()
    {
        $stringQuery = DB::string()->orderBy(['name' => 'asc', 'country' => 'desc'])->users();

        $this->assertSame("SELECT  *  FROM users  ORDER BY name asc, country desc", $stringQuery);
    }

    public function testLimit()
    {
        $stringQuery = DB::string()->limit(10)->users();

        $this->assertSame("SELECT  *  FROM users  LIMIT 10", $stringQuery);
    }

    public function testLimitStart()
    {
        $stringQuery = DB::string()->limit(5, 10)->users();

        $this->assertSame("SELECT  *  FROM users  LIMIT 10 OFFSET 5 ", $stringQuery);
    }

    public function testGet()
    {
        $person = DB::get('persons')->row();

        $this->assertIsObject($person);
    }

    public function testResult()
    {
        DB::duplicateCheck('name')->insert('persons', 
        [
            'name' => 'John'
        ]);

        $result = DB::persons()->result();

        $this->assertIsString($result[1]->name);
    }

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

    public function testVarTypes()
    {
        $this->assertIsArray(DB::varTypes());
    }

    public function testStringQueries()
    {
        $this->assertIsArray(DB::stringQueries());
    }

    public function testFunc()
    {
        $this->assertEquals('DATE(ID) ', DB::func('DATE', 'ID', true));
    }

    public function testString()
    {
        $this->assertEquals('SELECT DATE(ID)  FROM persons ', DB::string()->func('DATE', 'ID')->persons());
    }

    public function testSelectDefaultParameter()
    {
        $this->assertEquals('SELECT  *  FROM persons ', DB::string()->select()->persons());
    }

    public function testSelectWithDatabase()
    {
        $this->assertEquals('SELECT  db.table.id  FROM persons ', DB::string()->select('db.table.id')->persons());
    }

    public function testWhereEmptyNotNullLogical()
    {
        $this->assertEquals('SELECT  *  FROM persons  WHERE ( id =  "" or  id is null  ) ', DB::string()->whereEmpty('id', 'and')->persons());
    }

    public function testWhereNotEmptyNotNullLogical()
    {
        $this->assertEquals('SELECT  *  FROM persons  WHERE ( id != "" and  id is not null  ) ', DB::string()->whereNotEmpty('id', 'and')->persons());
    }

    public function testWhereEndLike()
    {
        $this->assertEquals("SELECT  *  FROM persons  WHERE name like '%word' ", DB::string()->whereEndLike('name', 'word')->persons());
    }

    public function testWhereInTable()
    {
        DB::whereInTable('name', 'addresses')->persons();

        $this->assertEquals("SELECT  *  FROM persons  WHERE name in (SELECT  *  FROM addresses ) ", DB::stringQuery());
    }

    public function testWhereInQuery()
    {
        DB::whereInQuery('name', 'addresses')->persons();

        $this->assertEquals("SELECT  *  FROM persons  WHERE name in (addresses) ", DB::stringQuery());
    }

    public function testJoinWithDatabase()
    {
        DB::join('database.addresses.username', 'addresses.username', 'left')->persons();

        $this->assertEquals("SELECT  *  FROM persons  LEFT JOIN database.addresses.username ON addresses.username ", DB::stringQuery());
    }

    public function testBasic()
    {
        DB::transStart()->persons()->transEnd();

        $this->assertEquals("SELECT  *  FROM persons ", DB::stringQuery());
    }

    public function testEscapeString()
    {
        $this->assertEquals("ozan\"", DB::escapeString('ozan"'));
        $this->assertEquals("ozan\"", DB::realEscapeString('ozan"'));
    }

    public function testAlias()
    {
        $this->assertEquals(" ( table )  AS tbl", DB::alias('table', 'tbl' ,true));
    }

    public function testAll()
    {
        DB::all()->persons();

        $this->assertEquals("SELECT  ALL  *  FROM persons ", DB::stringQuery());
    }

    public function testDistinct()
    {
        DB::distinct()->persons();

        $this->assertEquals("SELECT  DISTINCT  *  FROM persons ", DB::stringQuery());
    }

    public function testStraightJoin()
    {
        DB::straightJoin()->persons();

        $this->assertEquals("SELECT  STRAIGHT_JOIN  *  FROM persons ", DB::stringQuery());
    }

    public function testHighPriority()
    {
        DB::highPriority()->persons();

        $this->assertEquals("SELECT  HIGH_PRIORITY  *  FROM persons ", DB::stringQuery());
    }

    public function testPartition()
    {
        DB::partition()->persons();

        $this->assertEquals("SELECT  *  FROM persons PARTITION() ", DB::stringQuery());
    }

    public function testProcedure()
    {
        DB::procedure()->persons();

        $this->assertEquals("SELECT  *  FROM persons PROCEDURE() ", DB::stringQuery());
    }

    public function testOutfile()
    {
        DB::outFile('file')->persons();

        $this->assertEquals("SELECT  *  FROM persons INTO OUTFILE 'file' ", DB::stringQuery());
    }

    public function testDumpfile()
    {
        DB::dumpFile('file')->persons();

        $this->assertEquals("SELECT  *  FROM persons INTO DUMPFILE 'file' ", DB::stringQuery());
    }

    public function testReferences()
    {
        $this->assertEquals("REFERENCES table(column)", DB::references('table', 'column'));
    }

    public function testCharset()
    {
        DB::characterSet('UTF-8');
    }

    public function testCset()
    {
        $this->assertEquals("CHARACTER SET utf8 ", DB::cset(''));
    }

    public function testCollate()
    {
        $this->assertEquals("COLLATE utf8_general_ci ", DB::collate(''));
    }

    public function testInto()
    {
        DB::into('var1', 'var2')->persons();

        $this->assertEquals("SELECT  *  FROM persons CHARACTER SET UTF-8 INTO var1 , var2 ", DB::stringQuery());
    }

    public function testForUpdate()
    {
        DB::forUpdate()->persons();

        $this->assertEquals("SELECT  *  FROM persons  FOR UPDATE ", DB::stringQuery());
    }

    public function testLockInShareMode()
    {
        DB::lockInShareMode()->persons();

        $this->assertEquals("SELECT  *  FROM persons  LOCK IN SHARE MODE ", DB::stringQuery());
    }

    public function testSmallResult()
    {
        DB::smallResult()->persons();

        $this->assertEquals("SELECT  SQL_SMALL_RESULT  *  FROM persons ", DB::stringQuery());
    }

    public function testBigResult()
    {
        DB::bigResult()->persons();

        $this->assertEquals("SELECT  SQL_BIG_RESULT  *  FROM persons ", DB::stringQuery());
    }

    public function testBufferResult()
    {
        DB::bufferResult()->persons();

        $this->assertEquals("SELECT  SQL_BUFFER_RESULT  *  FROM persons ", DB::stringQuery());
    }

    public function testCache()
    {
        DB::cache()->persons();

        $this->assertEquals("SELECT  SQL_CACHE  *  FROM persons ", DB::stringQuery());
    }

    public function testNoCache()
    {
        DB::noCache()->persons();

        $this->assertEquals("SELECT  SQL_NO_CACHE  *  FROM persons ", DB::stringQuery());
    }

    public function testCalcFoundRows()
    {
        DB::calcFoundRows()->persons();

        $this->assertEquals("SELECT  SQL_CALC_FOUND_ROWS  *  FROM persons ", DB::stringQuery());
    }

    public function testStatus()
    {
        DB::status('persons');

        $this->assertEquals("SHOW TABLE STATUS FROM UnitTests/package-authentication/resources/testdb LIKE 'persons'", DB::stringQuery());
    }

    public function testFetchArray()
    {
        $this->assertIsArray(DB::persons()->fetchArray());
    }

    public function testFetchAssoc()
    {
        $this->assertIsArray(DB::persons()->fetchAssoc());
    }

    public function testFetchRow()
    {
        $this->assertIsArray(DB::persons()->fetchRow());
        $this->assertIsString(DB::persons()->fetchRow(true));
    }

    public function testFetch()
    {
        $this->assertIsArray(DB::persons()->fetch('assoc'));
        $this->assertIsArray(DB::persons()->fetch('array'));
        $this->assertIsArray(DB::persons()->fetch('row'));
    }

    public function testSwitchCase()
    {
        $case = DB::switchCase('name', ['as' => 'nm', 'default' => '10', 'else' => 5, 'white' => 'White'], true);

        $this->assertEquals(" CASE name ELSE 10 ELSE 5 WHEN white THEN White END  as nm ", $case);
    }

    public function testVartype()
    {
        $this->assertEquals(" INTEGER(11) ", DB::vartype('int', 11));
    }

    public function testPropery()
    {
        $this->assertEquals(" PRIMARY KEY(id) ", DB::property('primarykey', 'id'));

        DB::property('primarykey', 'id', false);
    }

    public function testDefaultValue()
    {
        $this->assertEquals(" DEFAULT(10) ", DB::defaultValue('10', true));

        DB::defaultValue('10', false);
    }

    public function testLike()
    {
        $this->assertEquals("%abc", DB::like('abc', 'ending'));
    }
}