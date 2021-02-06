<?php namespace ZN\Database;

class MySQLiForgeTest extends DatabaseExtends
{ 
    public function testDropForeignKey()
    {
        $this->assertEquals('ALTER TABLE test DROP  FOREIGN KEY  key;', (new MySQLi\DBForge)->dropForeignKey('test', 'key'));
    }

    public function testDropPrimaryKey()
    {
        $this->assertEquals('ALTER TABLE test DROP  PRIMARY KEY ;', (new MySQLi\DBForge)->dropPrimaryKey('test', 'key'));
    }
}