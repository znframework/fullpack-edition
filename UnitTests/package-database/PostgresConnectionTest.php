<?php namespace ZN\Database;

use DB;

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
            $this->assertTrue($db->error() ? $db->transRollback() : $db->transCommit());
        });
    }

    public function testInsertId()
    {
        $this->postgres(function($db)
        {
            $db->query('DELETE FROM persons');

            $db->query('INSERT INTO persons(id, name) VALUES (1, \'Tika\') RETURNING id;');

            $this->assertEquals(1, $db->insertId());
        });
    }

    public function testColumnData()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertIsArray($db->columnData());
            $this->assertIsObject($db->columnData('name'));
        });
    }

    public function testNumrows()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(1, $db->numRows());
        });
    }

    public function testColumns()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(['id', 'name'], $db->columns());
        });
    }

    public function testNumFields()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(2, $db->numFields());
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
        });
    }

    public function testFetchAssoc()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(['id' => 1, 'name' => 'Tika'], $db->fetchAssoc());
        });
    }

    public function testFetchRow()
    {
        $this->postgres(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals([1, 'Tika'], $db->fetchRow());
        });
    }

    public function testAffectedRows()
    {
        $this->postgres(function($db)
        {
            $db->query('DELETE FROM persons');

            $db->query('INSERT INTO persons(id, name) VALUES (1, \'Tika\');');

            $this->assertEquals(1, $db->affectedRows());
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