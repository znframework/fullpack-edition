<?php namespace ZN\Database;

use DB, DBForge;

class SQLServerConnectionTest extends DatabaseExtends
{
    public function testMake()
    {
        $db    = DB::new(self::sqlserver);
        $forge = DBForge::new(self::sqlserver);

        $forge->dropTable('example2');
        $forge->createTable('example2',
        [
            'id'   => [$db->int(11), $db->primaryKey(), $db->autoIncrement()],
            'name' => $db->varchar(255)
        ]);

        $this->assertTrue($db->multiQuery("INSERT INTO example2(name) VALUES ('zn')"));

        $this->assertTrue
        (
            $db->transStart()
               ->insert('example2', ['name' => 'framework']) 
               ->transEnd()
        );

        $this->assertFalse
        (
            $db->transStart()
               ->insert('examplex', ['name' => 'framework']) 
               ->transEnd()
        );

        $db->insert('example2', ['name' => 'web']);

        $this->assertEquals(3, $db->insertId());
        $this->assertEquals(1, $db->affectedRows());
        $this->assertIsArray($db->example2()->columnData());
        $this->assertEquals(['id', 'name'], $db->example2()->columns());
        $this->assertEquals(3, $db->example2()->totalRows());
        $this->assertEquals(2, $db->example2()->totalColumns());
        $this->assertIsString($db->version());
        $this->assertEquals(['1', 'zn'], $db->example2()->fetchRow());
        $this->assertEquals(['1', 'zn', 'id' => '1', 'name' => 'zn'], $db->example2()->fetchArray());
        $this->assertEquals(['id' => '1', 'name' => 'zn'], $db->example2()->fetchAssoc());
        $this->assertEquals('ozan', $db->realEscapeString("ozan"));
    }
}