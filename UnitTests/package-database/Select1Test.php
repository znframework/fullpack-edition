<?php namespace ZN\Database;

use DB;

class Select1Test extends DatabaseExtends
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
}