<?php namespace ZN\Database;

use DB, DBTool, DBForge;

class SQLServerForgeDCTest extends DatabaseExtends
{
    public function testDatabase()
    {
        $this->assertTrue(DBForge::new(self::sqlserver)->createDatabase('example'));
        $this->assertTrue(DBForge::new(self::sqlserver)->dropDatabase('example'));
    }

    public function testTable()
    {
        $forge = DBForge::new(self::sqlserver);
        $db    = DB::new(self::sqlserver);

        $forge->dropTable('example');
        $forge->dropTable('example2');

        $this->assertTrue($forge->createTable('example', 
        [
            'id'   => [$db->int(11), $db->autoIncrement(), $db->primaryKey()],
            'name' => $db->varchar(255)
        ]));        

        $this->assertTrue($forge->renameTable('example', 'example2'));
        $this->assertTrue(in_array('example2', DBTool::new(self::sqlserver)->listTables()));
        $this->assertTrue($forge->truncate('example2'));
        $this->assertTrue($forge->dropTable('example2'));
    }

    public function testColumn()
    {
        $forge = DBForge::new(self::sqlserver);
        $db    = DB::new(self::sqlserver);

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
            'date' => 'address'
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
        $forge = DBForge::new(self::sqlserver);
        $db    = DB::new(self::sqlserver);

        $forge->dropTable('example');
        $forge->dropTable('example2');

        $forge->createTable('example', 
        [
            'id'          => [$db->int(11), $db->notNull()],
            'name'        => [$db->varchar(255), $db->notNull()],
            'example2_id' => $db->int(11),
            'geo'         => 'GEOMETRY NOT NULL'
        ]);

        $forge->createTable('example2', 
        [
            'id'   => $db->int(11),
            'name' => [$db->varchar(255), $db->notNull()]
        ]);

        $this->assertTrue($forge->addPrimaryKey('example', 'id', 'exampleConstraintId'));
        $this->assertTrue($forge->dropPrimaryKey('example', 'exampleConstraintId'));
       
        $this->assertTrue($forge->addPrimaryKey('example', 'id, name', 'examplePrimaryKeys'));
        $this->assertTrue($forge->dropPrimaryKey('example', 'examplePrimaryKeys'));
    
        $this->assertEquals
        (
            'ALTER TABLE example ADD  CONSTRAINT  exampleForeignKeys  FOREIGN KEY (example2_id) REFERENCES example2(id);',
            $forge->string()->addForeignKey('example', 'example2_id', 'example2', 'id', 'exampleForeignKeys')
        ); 
        $this->assertEquals
        (
            'ALTER TABLE example DROP  CONSTRAINT exampleForeignKeys;', 
            $forge->string()->dropForeignKey('example', 'exampleForeignKeys')
        );

        
        $this->assertTrue($forge->createIndex('exampleIndex', 'example', 'name'));
        $this->assertTrue($forge->dropIndex('exampleIndex', 'example'));
        $this->assertTrue($forge->createUniqueIndex('exampleIndex', 'example', 'name'));
        $this->assertFalse($forge->createSpatialIndex('geoIndex', 'example', 'geo'));
        $this->assertFalse($forge->createFulltextIndex('PK_example2', 'example2', 'name'));
    }
}