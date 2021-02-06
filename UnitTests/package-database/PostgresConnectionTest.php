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

    public function testConnectionWithDNS()
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

    public function testExtras()
    {
        $this->assertNull((new Postgres\DBForge)->extras([]));
    }

    public function testModifyColumn()
    {
        $this->assertEquals('ALTER TABLE test ALTER COLUMN id TYPE int;', (new Postgres\DBForge)->modifyColumn('test', ['id' => 'int']));
    }

    public function testRenameColumn()
    {
        $this->assertEquals('ALTER TABLE test RENAME COLUMN id TO int;', (new Postgres\DBForge)->renameColumn('test', ['id' => 'int']));
    }

    public function testAddColumn()
    {
        $this->assertEquals('ALTER TABLE test ADD id int;', (new Postgres\DBForge)->addColumn('test', ['id' => 'int']));
    }

    public function testListDatabases()
    {
        $tool = \DBTool::new(self::postgres);

        $this->assertEquals('test', $tool->listDatabases()[1]);
    }

    public function testListTables()
    {
        $tool = \DBTool::new(self::postgres);

        $this->assertEquals(['persons'], $tool->listTables());
    }

    public function testStatusTables()
    {
        $tool = \DBTool::new(self::postgres);

        $this->assertFalse($tool->statusTables());
    }

    public function testOptimizeTables()
    {
        $tool = \DBTool::new(self::postgres);

        $this->assertFalse($tool->optimizeTables());
    }

    public function testRepairTables()
    {
        $tool = \DBTool::new(self::postgres);

        $this->assertFalse($tool->repairTables());
    }

    public function testBody()
    {
        $this->assertNull((new Postgres\DBTrigger)->body('id'));
    }

    public function testDropTrigger()
    {
        $this->assertEquals('DROP TRIGGER ON test ex;', (new Postgres\DBTrigger)->dropTrigger('test', 'ex'));
    }

    public function testUserName()
    {
        $this->assertNull((new Postgres\DBUser)->name('test'));
    }

    public function testUserCreate()
    {
        $this->assertEquals('CREATE USER test', (new Postgres\DBUser)->create('test'));
    }

    public function testUserDrop()
    {
        $this->assertEquals('DROP USER test', (new Postgres\DBUser)->drop('test'));
    }

    public function testUserAlter()
    {
        $user = new Postgres\DBUser;

        $user->option('PASSWORD', 'test');

        $this->assertEquals('ALTER USER test PASSWORD test', $user->alter('test'));
    }
}