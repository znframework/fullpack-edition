<?php namespace ZN\Database;

use DB, DBTool, DBForge;

class MySQLiForgeTest extends DatabaseExtends
{
    public function testDatabase()
    {
        $this->assertTrue(DBForge::new(self::mysqli)->createDatabase('example'));
        $this->assertTrue(DBForge::new(self::mysqli)->dropDatabase('example'));
    }

    public function testTable()
    {
        $forge = DBForge::new(self::mysqli);
        $db    = DB::new(self::mysqli);

        $forge->dropTable('example');
        $forge->dropTable('example2');

        $this->assertTrue($forge->createTable('example', 
        [
            'id'   => [$db->int(11), $db->autoIncrement(), $db->primaryKey()],
            'name' => $db->varchar(255)
        ]));        
        $this->assertTrue($forge->renameTable('example', 'example2'));
        $this->assertTrue($forge->truncate('example2'));
        $this->assertTrue($forge->dropTable('example2'));
    }

    public function testColumn()
    {
        $forge = DBForge::new(self::mysqli);
        $db    = DB::new(self::mysqli);

        $forge->dropTable('example');

        $forge->createTable('example', 
        [
            'id'   => [$db->int(11), $db->autoIncrement(), $db->primaryKey()],
            'name' => $db->varchar(255)
        ]);

        $this->assertTrue($forge->addColumn('example', 
        [
            'date' => $db->datetime()
        ]));
        $this->assertContains('date', $db->example()->columns());
        $this->assertTrue($forge->renameColumn('example', 
        [
            'date address' => $db->datetime()
        ]));
        
        $this->assertContains('address', $db->example()->columns());
        $this->assertTrue($forge->modifyColumn('example', 
        [
            'address' => $db->varchar(255)
        ]));
        $this->assertContains('address', $db->example()->columns());
        $this->assertTrue($forge->dropColumn('example', 'address'));
    }

    public function testKey()
    {
        $forge = DBForge::new(self::mysqli);
        $db    = DB::new(self::mysqli);

        $forge->dropTable('example');
        $forge->dropTable('example2');

        $forge->createTable('example', 
        [
            'id'          => $db->int(11),
            'name'        => $db->varchar(255),
            'example2_id' => $db->int(11),
            'geo'         => 'GEOMETRY NOT NULL'
        ]);

        $forge->createTable('example2', 
        [
            'id'   => $db->int(11),
            'name' => $db->varchar(255)
        ]);

        $this->assertTrue($forge->addPrimaryKey('example', 'id'));
        $this->assertTrue((bool) $db->example()->columnData()['id']->primaryKey);
        $this->assertTrue($forge->dropPrimaryKey('example', 'id'));
        $this->assertFalse((bool) $db->example()->columnData()['id']->primaryKey);
        $this->assertTrue($forge->addPrimaryKey('example', 'id, name', 'examplePrimaryKeys'));
        $this->assertTrue((bool) $db->example()->columnData()['id']->primaryKey);
        $this->assertTrue((bool) $db->example()->columnData()['name']->primaryKey);
        $this->assertTrue($forge->dropPrimaryKey('example', 'examplePrimaryKeys'));
        $this->assertFalse((bool) $db->example()->columnData()['id']->primaryKey);
        $this->assertFalse((bool) $db->example()->columnData()['name']->primaryKey);
        $this->assertEquals
        (
            'ALTER TABLE example ADD  CONSTRAINT  exampleForeignKeys  FOREIGN KEY (example2_id) REFERENCES example2(id);',
            $forge->string()->addForeignKey('example', 'example2_id', 'example2', 'id', 'exampleForeignKeys')
        ); 
        $this->assertEquals
        (
            'ALTER TABLE example DROP  FOREIGN KEY  exampleForeignKeys;', 
            $forge->string()->dropForeignKey('example', 'exampleForeignKeys')
        );
        $this->assertTrue($forge->createIndex('exampleIndex', 'example', 'name'));
        $this->assertTrue($forge->dropIndex('exampleIndex', 'example'));
        $this->assertTrue($forge->createFulltextIndex('exampleIndex', 'example', 'name'));
        $forge->dropIndex('exampleIndex', 'example');
        $this->assertTrue($forge->createUniqueIndex('exampleIndex', 'example', 'name'));
        $forge->dropIndex('exampleIndex', 'example');
        $this->assertTrue($forge->createSpatialIndex('geoIndex', 'example', 'geo'));
    }
}