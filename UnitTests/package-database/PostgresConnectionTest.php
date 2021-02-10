<?php namespace ZN\Database;

class PostgresConnectionTest extends DatabaseExtends
{ 
    public function testConnection()
    {
        $this->postgres();
    }

    public function testConnectionFail()
    {
        try
        {
            (new Postgres\DB)->connect([]);
        }
        catch( Exception\ConnectionErrorException $e )
        {
            $this->assertIsString($e->getMessage());
        }
    }

    public function testConnectionWithDSN()
    {
        try
        {
            (new Postgres\DB)->connect(['dsn' => 'host=localhost port=5432 dbname=test user=postgres password=postgres', 'charset' => 'utf-8']);
        }
        catch( Exception\ConnectionErrorException $e )
        {
            echo $e->getMessage();
        }
    }

    public function testExecFalse()
    {
        $this->postgres(function($db)
        {
            $this->assertFalse($db->exec(''));
        });
    }

    public function testExec()
    {
        $this->postgres(function($db)
        {
            $db->exec('DELETE FROM persons');
            $db->exec('CREATE TABLE IF NOT EXISTS persons (id SERIAL PRIMARY KEY, name varchar(255))');

            $this->assertEmpty($db->error());
        });
    }

    public function testQuery()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEmpty($db->error());
        });
    }

    public function testMultiQuery()
    {
        $this->postgres(function($db)
        {
            $db->multiQuery('SELECT * FROM persons');

            $this->assertEmpty($db->error());
        });
    }

    public function testTransQuery()
    {
        $this->postgres(function($db)
        {
            $db->transStart();
            $db->query('SELECT * FROM persons');
            $db->transRollback();
            $db->transCommit();
        });
    }

    public function testInsertId()
    {
        $this->postgres(function($db)
        {
            $db->query('DELETE FROM persons');

            $db->query('INSERT INTO persons(id, name) VALUES (1, \'Tika\') RETURNING id;');

            $this->assertEquals(1, $db->insertId());
            
            $db->query('');

            $this->assertEquals(false, $db->insertId());
        });
    }

    public function testColumnData()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertIsArray($db->columnData());
            $this->assertIsObject($db->columnData('name'));

            $db->query('');

            $this->assertFalse($db->columnData());
        });
    }

    public function testNumrows()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(1, $db->numRows());

            $db->query('');

            $this->assertEquals(0, $db->numRows());
        });
    }

    public function testColumns()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(['id', 'name'], $db->columns());

            $db->query('');

            $this->assertEquals([], $db->columns());
        });
    }

    public function testNumFields()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(2, $db->numFields());

            $db->query('');

            $this->assertEquals(0, $db->numFields());
        });
    }

    public function testRealEscapeString()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals("ozan''", $db->realEscapeString("ozan'"));
        });
    }

    public function testError()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM personsx');

            $this->assertIsString($db->error());

            $db->query('SELECT * FROM persons');

            $this->assertEmpty($db->error());
        });
    }

    public function testFetchArray()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals([1, 'Tika', 'id' => 1, 'name' => 'Tika'], $db->fetchArray());

            $db->query('');

            $this->assertEquals([], $db->fetchArray());
        });
    }

    public function testFetchAssoc()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(['id' => 1, 'name' => 'Tika'], $db->fetchAssoc());

            $db->query('');

            $this->assertEquals([], $db->fetchAssoc());
        });
    }

    public function testFetchRow()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals([1, 'Tika'], $db->fetchRow());

            $db->query('');

            $this->assertEquals([], $db->fetchRow());
        });
    }

    public function testAffectedRows()
    {
        $this->postgres(function($db)
        {
            $db->query('DELETE FROM persons');

            $db->query('INSERT INTO persons(id, name) VALUES (1, \'Tika\');');

            $this->assertEquals(1, $db->affectedRows());

            $db->query('');

            $this->assertEquals(0, $db->affectedRows());
        });
    }

    public function testClose()
    {
        $this->postgres(function($db)
        {
            $this->assertTrue($db->close());
        });
    }
    
    public function testVersion()
    {
        $this->postgres(function($db)
        {
            $this->assertIsArray($db->version());
        });
    }
}