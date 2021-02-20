<?php namespace ZN\Database;

use DB, DBForge;

class PostgresConnectionTest extends DatabaseExtends
{
    public function testMake()
    {
        $db    = DB::new(self::postgres);
        $forge = DBForge::new(self::postgres);

        $forge->dropTable('example2');
        $forge->createTable('example2',
        [
            'id'   => [$db->int(11), $db->primaryKey()],
            'name' => $db->varchar(255)
        ]);

        $this->assertTrue($db->multiQuery("INSERT INTO example2(id, name) VALUES (1, 'zn')"));

        $this->assertTrue
        (
            $db->transStart()
               ->insert('example2', ['id' => 2, 'name' => 'framework']) 
               ->transEnd()
        );

        $this->assertFalse
        (
            $db->transStart()
               ->insert('examplex', ['id' => 2, 'name' => 'framework']) 
               ->transEnd()
        );

        $db->insert('example2', ['id' => 3, 'name' => 'web']);

        $this->assertEquals(3, $db->insertId());
        $this->assertEquals(1, $db->affectedRows());
        $this->assertIsArray($db->example2()->columnData());
        $this->assertEquals(['id', 'name'], $db->example2()->columns());
        $this->assertEquals(3, $db->example2()->totalRows());
        $this->assertIsString($db->version());
        $this->assertEquals(['1', 'zn'], $db->example2()->fetchRow());
        $this->assertEquals(['1', 'zn', 'id' => '1', 'name' => 'zn'], $db->example2()->fetchArray());
        $this->assertEquals(['id' => '1', 'name' => 'zn'], $db->example2()->fetchAssoc());
        $this->assertEquals('ozan\'\'', $db->realEscapeString("ozan'"));
    }
}