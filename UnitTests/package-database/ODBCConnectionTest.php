<?php namespace ZN\Database;

class ODBCConnectionTest extends DatabaseExtends
{ 
    public function testConnection()
    {
        $this->odbc();
    }

    public function testConnectionFail()
    {
        try
        {
            (new ODBC\DB)->connect([]);
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
            (new ODBC\DB)->connect(['dsn' => 'Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=' . self::default . 'package-database/resources/testdb.mdb']);
        }
        catch( Exception\ConnectionErrorException $e )
        {
            echo $e->getMessage();
        }
    }

    public function testExecFalse()
    {
        $this->odbc(function($db)
        {
            $this->assertFalse($db->exec(''));
        });
    }

    public function testExec()
    {
        $this->odbc(function($db)
        {
            $db->exec('DELETE FROM persons');
            $db->exec('CREATE TABLE persons (id int, name varchar(255))');
        });
    }

    public function testQuery()
    {
        $this->odbc(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEmpty($db->error());
        });
    }

    public function testMultiQuery()
    {
        $this->odbc(function($db)
        {
            $db->multiQuery('SELECT * FROM persons');

            $this->assertEmpty($db->error());
        });
    }

    public function testTransQuery()
    {
        $this->odbc(function($db)
        {
            $db->transStart();
            $db->query('SELECT * FROM persons');
            $db->transRollback();
            $db->transCommit();

            $this->assertEmpty($db->error());
        });
    }

    public function testInsertId()
    {
        $this->odbc(function($db)
        {
            $db->query('DELETE FROM persons');

            $db->query("INSERT INTO persons(id, name) VALUES (1, 'Tika')");

            $this->assertFalse($db->insertId());
        });
    }

    public function testColumnData()
    {
        $this->odbc(function($db)
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
        $this->odbc(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(-1, $db->numRows());

            $db->query('');

            $this->assertEquals(0, $db->numRows());
        });
    }

    public function testColumns()
    {
        $this->odbc(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(['id', 'name'], $db->columns());

            $db->query('');

            $this->assertEquals([], $db->columns());
        });
    }

    public function testNumFields()
    {
        $this->odbc(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(2, $db->numFields());

            $db->query('');

            $this->assertEquals(0, $db->numFields());
        });
    }

    public function testRealEscapeString()
    {
        $this->odbc(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals("ozan\'", $db->realEscapeString("ozan'"));
        });
    }

    public function testError()
    {
        $this->odbc(function($db)
        {
            $db->query('SELECT * FROM personsx');

            $this->assertIsString($db->error());
        });
    }

    public function testFetchArray()
    {
        $this->odbc(function($db)
        {
            $db->query("INSERT INTO persons(id, name) VALUES (1, 'Tika')");

            $db->query('SELECT * FROM persons');

            $this->assertEquals(['id' => '1', 'name' => 'Tika'], $db->fetchArray());

            $db->query('');

            $this->assertEquals([], $db->fetchArray());

            $db->query('DELETE FROM persons');
        });
    }

    public function testFetchAssoc()
    {
        $this->odbc(function($db)
        {
            $db->query("INSERT INTO persons(id, name) VALUES (1, 'Tika')");

            $db->query('SELECT * FROM persons');

            $this->assertEquals(['id' => '1', 'name' => 'Tika'], $db->fetchAssoc());

            $db->query('');

            $this->assertEquals([], $db->fetchAssoc());

            $db->query('DELETE FROM persons');
        });
    }

    public function testFetchRow()
    {
        $this->odbc(function($db)
        {
            $db->query("INSERT INTO persons(id, name) VALUES (1, 'Tika')");

            $db->query('SELECT * FROM persons');

            $this->assertEquals(['id' => '1', 'name' => 'Tika'], $db->fetchRow());

            $db->query('');

            $this->assertEquals([], $db->fetchRow());

            $db->query('DELETE FROM persons');
        });
    }

    public function testAffectedRows()
    {
        $this->odbc(function($db)
        {
            $db->query('DELETE FROM persons');

            $db->query("INSERT INTO persons(id, name) VALUES (1, 'Tika')");

            $this->assertEquals(0, $db->affectedRows());
        });
    }

    public function testClose()
    {
        $this->odbc(function($db)
        {
            $this->assertNull($db->close());
        });
    }
    
    public function testVersion()
    {
        $this->odbc(function($db)
        {
            $this->assertFalse($db->version());
        });
    }
}