<?php namespace ZN\Database;

class MySQLiConnectionTest extends DatabaseExtends
{
    public function testConnection()
    {
        $this->mysqli();
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
        $this->mysqli(function($db)
        {
            $this->assertFalse($db->exec(''));
        });
    }

    public function testExec()
    {
        $this->mysqli(function($db)
        {
            $db->exec('DROP TABLE persons');
            $db->exec('CREATE TABLE persons (id INT(11))');

            $this->assertEmpty($db->error());
        });
    }

    public function testQuery()
    {
        $this->mysqli(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEmpty($db->error());
        });
    }

    public function testMultiQuery()
    {
        $this->mysqli(function($db)
        {
            $db->multiQuery('SELECT * FROM persons');

            $this->assertEmpty($db->error());
        });
    }

    public function testTransQuery()
    {
        $this->mysqli(function($db)
        {
            $db->transStart();
            $db->query('SELECT * FROM persons');
            $db->transRollback();
            $db->transCommit();
        });
    }

    public function testInsertId()
    {
        $this->mysqli(function($db)
        {
            $db->query('DELETE FROM persons');

            $db->query('INSERT INTO persons (id) VALUES (2)');
  
            $this->assertEquals(0, $db->insertId());
            
            $db->query('');

            $this->assertEquals(false, $db->insertId());
        });
    }

    public function testColumnData()
    {
        $this->mysqli(function($db)
        {
            $db->query('SELECT * FROM persons');


            $this->assertIsArray($db->columnData(NULL));
            $this->assertIsObject($db->columnData('id'));

            $db->query('');

            $this->assertFalse($db->columnData(NULL));
        });
    }

    public function testNumrows()
    {
        $this->mysqli(function($db)
        {
            $this->assertEquals(false, $db->numRows());
        });
    }

    public function testColumns()
    {
        $this->mysqli(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(['id'], $db->columns());

            $db->query('');

            $this->assertEquals([], $db->columns());
        });
    }

    public function testNumFields()
    {
        $this->mysqli(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(1, $db->numFields());

            $db->query('');

            $this->assertEquals(0, $db->numFields());
        });
    }

    public function testRealEscapeString()
    {
        $this->mysqli(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals("ozan\'", $db->realEscapeString("ozan'"));
        });
    }

    public function testError()
    {
        $this->mysqli(function($db)
        {
            $db->query('SELECT * FROM personsx');

            $this->assertIsString($db->error());

            $db->query('SELECT * FROM persons');

            $this->assertEmpty($db->error());
        });
    }

    public function testFetchArray()
    {
        $this->mysqli(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals([2, 'id' => 2], $db->fetchArray());

            $db->query('');

            $this->assertEquals([], $db->fetchArray());
        });
    }

    public function testFetchAssoc()
    {
        $this->mysqli(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals(['id' => 2], $db->fetchAssoc());

            $db->query('');

            $this->assertEquals([], $db->fetchAssoc());
        });
    }

    public function testFetchRow()
    {
        $this->mysqli(function($db)
        {
            $db->query('SELECT * FROM persons');

            $this->assertEquals([2], $db->fetchRow());

            $db->query('');

            $this->assertEquals([], $db->fetchRow());
        });
    }

    public function testAffectedRows()
    {
        $this->mysqli(function($db)
        {
            $db->query('DELETE FROM persons');

            $db->query('INSERT INTO persons (id) VALUES (1);');

            $this->assertEquals(1, $db->affectedRows());
        });
    }

    public function testClose()
    {
        $this->mysqli(function($db)
        {
            $this->assertTrue($db->close());
        });
    }

    public function testVersion()
    {
        $this->mysqli(function($db)
        {
            $this->assertIsString($db->version());
        });
    }
}