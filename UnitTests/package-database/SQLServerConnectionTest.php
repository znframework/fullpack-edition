<?php namespace ZN\Database;

class SQLServerConnectionTest extends DatabaseExtends
{ 
    public function testConnection()
    {
        $this->assertNull($this->sqlserver());
    }

    public function testConnectionFail()
    {
        try
        {
            (new SQLServer\DB)->connect([]);
        }
        catch( Exception\ConnectionErrorException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testExecFalse()
    {
        $this->sqlserver(function($db)
        {
            $this->assertFalse($db->exec(''));
        });
    }

    public function testExec()
    {
        $this->sqlserver(function($db)
        {
            $db->exec('DROP TABLE persons');
            $db->exec('CREATE TABLE persons (id INT)');

            $this->assertEmpty($db->error());
        });
    }

    public function testQuery()
    {
        $this->sqlserver(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEmpty($db->error());
        });
    }

    public function testMultiQuery()
    {
        $this->sqlserver(function($db)
        {
            $db->multiQuery('SELECT * FROM persons');

            $this->assertEmpty($db->error());
        });
    }

    public function testTransQuery()
    {
        $this->sqlserver(function($db)
        {
            $db->transStart();
            $db->query('SELECT * FROM persons');
            $db->transRollback();
            $db->transCommit();
        });
    }

    public function testInsertId()
    {
        $this->sqlserver(function($db)
        {
            $db->query('DELETE FROM persons');

            $db->query('INSERT INTO persons (id) VALUES (2)');
  
            print_r($db->insertId());
            
            $db->query('');

            $this->assertEquals(false, $db->insertId());
        });
    }

    public function testColumnData()
    {
        $this->sqlserver(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertIsArray($db->columnData());
            $this->assertIsObject($db->columnData('id'));

            $db->query('');

            $this->assertFalse($db->columnData());
        });
    }

    public function testNumrows()
    {
        $this->sqlserver(function($db)
        {
            $this->assertEquals(false, $db->numRows());
        });
    }

    public function testColumns()
    {
        $this->sqlserver(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(['id'], $db->columns());

            $db->query('');

            $this->assertEquals([], $db->columns());
        });
    }

    public function testNumFields()
    {
        $this->sqlserver(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(1, $db->numFields());

            $db->query('');

            $this->assertEquals(0, $db->numFields());
        });
    }

    public function testRealEscapeString()
    {
        $this->sqlserver(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals("ozan\'", $db->realEscapeString("ozan'"));
        });
    }

    public function testError()
    {
        $this->sqlserver(function($db)
        {
            $db->query('SELECT * FROM personsx');

            $this->assertIsString($db->error());

            $db->query('SELECT * FROM persons');

            $this->assertEmpty($db->error());
        });
    }

    public function testFetchArray()
    {
        $this->sqlserver(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals([2, 'id' => 2], $db->fetchArray());

            $db->query('');

            $this->assertEquals([], $db->fetchArray());
        });
    }

    public function testFetchAssoc()
    {
        $this->sqlserver(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(['id' => 2], $db->fetchAssoc());

            $db->query('');

            $this->assertEquals([], $db->fetchAssoc());
        });
    }

    public function testFetchRow()
    {
        $this->sqlserver(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals([2], $db->fetchRow());

            $db->query('');

            $this->assertEquals([], $db->fetchRow());
        });
    }

    public function testAffectedRows()
    {
        $this->sqlserver(function($db)
        {
            $db->query('DELETE FROM persons');

            $db->query('INSERT INTO persons (id) VALUES (1);');

            $this->assertEquals(1, $db->affectedRows());

            $db->query('');

            $this->assertEquals(0, $db->affectedRows());
        });
    }

    public function testClose()
    {
        $this->sqlserver(function($db)
        {
            $this->assertTrue($db->close());
        });
    }

    public function testVersion()
    {
        $this->sqlserver(function($db)
        {
            $this->assertIsString($db->version());
        });
    }

    public function testLimit()
    {
        $this->sqlserver(function($db)
        {
            $this->assertEquals(' OFFSET 1 ROWS FETCH NEXT 5 ROWS ONLY', $db->limit(1, 5));
            $this->assertEquals(' OFFSET 0 ROWS FETCH NEXT 5 ROWS ONLY', $db->limit(5));
        });
    }

    public function testCleanLimit()
    {
        $this->sqlserver(function($db)
        {
            $this->assertEquals('', $db->cleanLimit('OFFSET 1 ROWS FETCH NEXT 5 ROWS ONLY'));
        });
    }

    public function testGetLimitValues()
    {
        $this->sqlserver(function($db)
        {
            $this->assertEquals
            ([
                'OFFSET 1 ROWS FETCH NEXT 5 ROWS ONLY',
                'start' => '1',
                1 => '1',
                'limit' => '5',
                2 => '5'
            ], $db->getLimitValues('OFFSET 1 ROWS FETCH NEXT 5 ROWS ONLY'));
        });
    }
}