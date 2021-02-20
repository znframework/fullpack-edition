<?php namespace ZN\Database;

use DB, DBTool, DBForge;

class SQLiteForgeDCTest extends DatabaseExtends
{
    public function testDatabase()
    {
        $this->assertFalse(DBForge::new(self::sqlite)->createDatabase('example'));
        $this->assertFalse(DBForge::new(self::sqlite)->dropDatabase('example'));
    }

    public function testTable()
    {
        $forge = DBForge::new(self::sqlite);
        $db    = DB::new(self::sqlite);

        $forge->dropTable('example');
        $forge->dropTable('example2');

        $this->assertTrue($forge->createTable('example', 
        [
            'id'   => [$db->int(11), $db->primaryKey(), $db->autoIncrement()],
            'name' => $db->varchar(255)
        ]));       
        
        $this->assertTrue($forge->renameTable('example', 'example2'));
        $this->assertTrue($forge->truncate('example2'));
        $this->assertTrue($forge->dropTable('example2'));
    }

    public function testColumn()
    {
        $forge = DBForge::new(self::sqlite);
        $db    = DB::new(self::sqlite);

        $forge->dropTable('example');

        $forge->createTable('example', 
        [
            'id'   => [$db->int(11), $db->primaryKey(), $db->autoIncrement()],
            'name' => $db->varchar(255)
        ]);

        $this->assertTrue($forge->addColumn('example', 
        [
            'date' => $db->datetime()
        ]));
        
        $this->assertIsBool($forge->renameColumn('example', ['date' => 'address']));
        $this->assertContains('address', $db->example()->columns());
        $this->assertFalse($forge->modifyColumn('example', 
        [
            'address' => $db->varchar(255)
        ]));
        $this->assertFalse($forge->dropColumn('example', 'address'));
    }

    public function testKey()
    {
        $forge = DBForge::new(self::sqlite);
        $db    = DB::new(self::sqlite);

        $forge->dropTable('example');
        $forge->dropTable('example2');

        $forge->createTable('example', 
        [
            'id'          => $db->int(11),
            'name'        => $db->varchar(255),
            'example2_id' => $db->int(11)
        ]);

        $forge->createTable('example2', 
        [
            'id'   => $db->int(11),
            'name' => $db->varchar(255)
        ]);

        $this->assertFalse($forge->addPrimaryKey('example', 'id'));
        $this->assertFalse($forge->dropPrimaryKey('example', 'id'));
        $this->assertFalse($forge->addForeignKey('example', 'example2_id', 'example2', 'id', 'exampleForeignKeys')); 
        $this->assertFalse($forge->dropForeignKey('example', 'exampleForeignKeys'));

        $this->assertTrue($forge->createIndex('exampleIndex', 'example', 'name'));
        $this->assertTrue($forge->dropIndex('exampleIndex'));
        $this->assertTrue($forge->createUniqueIndex('exampleIndex', 'example', 'name'));
        $this->assertTrue($forge->dropIndex('exampleIndex'));  
        
        $this->assertFalse($forge->createSpatialIndex('geoIndex', 'example', 'geo'));
    }
}