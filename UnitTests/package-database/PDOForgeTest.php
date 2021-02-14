<?php namespace ZN\Database;

class PDOForgeTest extends DatabaseExtends
{ 
    public function testDropForeignKey()
    {
        $this->assertEquals('ALTER TABLE test DROP  FOREIGN KEY  constraint;', (new PDO\DBForge)->dropForeignKey('test', 'constraint'));
    }

    public function testDropPrimaryKey()
    {
        $this->assertEquals('ALTER TABLE test DROP  PRIMARY KEY ;', (new PDO\DBForge)->dropPrimaryKey('test'));
    }
}