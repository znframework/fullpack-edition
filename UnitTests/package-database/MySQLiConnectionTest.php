<?php namespace ZN\Database;

use DB;

class MySQLiConnectionTest extends DatabaseExtends
{
    const connection = ['driver' => 'mysqli', 'user' => 'root', 'host' => 'localhost', 'database' => 'test', 'password' => ''];

    public function testExec()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $this->assertFalse($db->exec(''));
        $this->assertIsBool($db->exec('CREATE TABLE persons (name VARCHAR(255))'));
    }

    public function testMultiQuery()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $this->assertFalse($db->multiQuery(''));
        $this->assertIsBool($db->multiQuery('SELECT * FROM persons'));
    }

    public function testSelect()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $this->assertIsBool($db->exec('INSERT INTO persons (name) VALUES ("Ozan")'));

        $db->query('SELECT * FROM persons');

        $db->exec('DELETE FROM persons WHERE name = "Ozan"');
    }

    public function testRealEscapeString()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $this->assertIsBool($db->exec('INSERT INTO persons (name) VALUES ("Ozan")'));

        $db->query('SELECT * FROM persons');

        $this->assertEquals('ozan', $db->realEscapeString('ozan'));

        $db->exec('DELETE FROM persons WHERE name = "Ozan"');
    }

    public function testInsertId()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $this->assertIsBool($db->exec('INSERT INTO persons (name) VALUES ("Ozan")'));

        $this->assertEquals(0, $db->insertId());

        $db->exec('DELETE FROM persons WHERE name = "Ozan"');
    }

    public function testFetchArray()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $db->exec('INSERT INTO persons (name) VALUES ("Ozan")');

        $db->query('SELECT * FROM persons');

        $db->fetchArray();

        $db->exec('DELETE FROM persons WHERE name = "Ozan"');
    }

    public function testFetchAssoc()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $db->exec('INSERT INTO persons (name) VALUES ("Ozan")');

        $db->query('SELECT * FROM persons');

        $db->fetchAssoc();

        $db->exec('DELETE FROM persons WHERE name = "Ozan"');
    }

    public function testFetchRow()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $db->exec('INSERT INTO persons (name) VALUES ("Ozan")');

        $db->query('SELECT * FROM persons');

        $db->fetchRow();

        $db->exec('DELETE FROM persons WHERE name = "Ozan"');
    }

    public function testNumRows()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $db->exec('INSERT INTO persons (name) VALUES ("Ozan")');

        $db->query('SELECT * FROM persons');

        $db->numRows();

        $db->exec('DELETE FROM persons WHERE name = "Ozan"');
    }

    public function testNumFields()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $db->exec('INSERT INTO persons (name) VALUES ("Ozan")');

        $db->query('SELECT * FROM persons');

        $db->numFields();
       
        $db->exec('DELETE FROM persons WHERE name = "Ozan"');
    }

    public function testColumns()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $db->exec('INSERT INTO persons (name) VALUES ("Ozan")');

        $db->query('SELECT * FROM persons');

        $db->columns();

        $db->exec('DELETE FROM persons WHERE name = "Ozan"');
    }

    public function testColumnData()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $db->exec('INSERT INTO persons (name) VALUES ("Ozan")');

        $db->query('SELECT * FROM persons');

        $db->columnData('name');

        $db->exec('DELETE FROM persons WHERE name = "Ozan"');
    }

    public function testTransQuery()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $db->exec('INSERT INTO persons (name) VALUES ("Ozan")');

        $db->query('SELECT * FROM persons');

        $db->transStart();
        $db->exec('CREATE TABLE persons (id INT(6) AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255))');
        $db->error() ? $db->transRollback() : $db->transCommit();

        $db->exec('DELETE FROM persons WHERE name = "Ozan"');
    }

    public function testError()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $this->assertIsBool($db->exec('INSERT INTO persons (name) VALUES ("Ozan")'));

        $db->query('SELECT * FROM persons');

        $this->assertFalse($db->error());

        $db->exec('DELETE FROM persons WHERE name = "Ozan"');
    }

    public function testAffectedRows()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $db->exec('INSERT INTO persons (name) VALUES ("Ozan")');

        $db->exec('DELETE FROM persons WHERE name = "Ozan"');

        $db->affectedRows();
    }

    public function testClose()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $db->close();
    }

    public function testVersion()
    {
        $db = new MySQLi\DB;

        $db->connect(self::connection);

        $db->version();
    }
}