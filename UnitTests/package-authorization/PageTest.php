<?php namespace ZN\Authorization;

use DB;
use Config;
use DBForge;
use Permission;

class PageTest extends AuthorizationExtends
{
    public function testPageUpdateProcess()
    {
        $_SERVER['PATH_INFO'] = 'update';

        $this->assertTrue (Permission::page(1));
        $this->assertTrue (Permission::page(2));
        $this->assertFalse(Permission::page(3));
        $this->assertFalse(Permission::page(4));
    }

    public function testPageDeleteProcess()
    {
        $_SERVER['PATH_INFO'] = 'delete';

        $this->assertTrue (Permission::page(1));
        $this->assertFalse(Permission::page(2));
        $this->assertFalse(Permission::page(3));
        $this->assertFalse(Permission::page(4));
    }

    public function testPageCreateProcess()
    {
        $_SERVER['PATH_INFO'] = 'create';

        $this->assertTrue (Permission::page(1));
        $this->assertTrue (Permission::page(2));
        $this->assertTrue (Permission::page(3));
        $this->assertFalse(Permission::page(4));
    }

    public function testPageRealpath()
    {
        $this->assertTrue (Permission::realpath('create')->page(3));
        $this->assertFalse(Permission::realpath('delete')->page(3));
        $this->assertFalse(Permission::realpath('update')->page(3));
    }

    public function testUsePredfined()
    {
        $this->assertEmpty(Page::use());
    }

    public function testWithDatabase()
    {
        Config::database('database', \ZN\Database\DatabaseExtends::postgres);

        DBForge::createTable('perms',
        [
            'role'  => [DB::int()],
            'rules' => [DB::varchar(255)]
        ]);
        
        DB::insert('perms',
        [
            'role'  => 1,
            'rules' => 'all'
        ]);

        DB::insert('perms',
        [
            'role'  => 2,
            'rules' => json_encode(['delete'])
        ]);

        DB::insert('perms',
        [
            'role'  => 3,
            'rules' => json_encode(['update', 'delete'])
        ]);

        DB::insert('perms',
        [
            'role'  => 4,
            'rules' => 'any'
        ]);

        $_SERVER['PATH_INFO'] = 'update';

        $this->assertTrue(Permission::page(3, ['noperm' => 'perms[role]:rules'], function()
        {
            return true;
        }));

        DBForge::truncate('perms');

        $this->assertEquals(['update', 'delete'], PermissionExtends::getNopermRules());
        $this->assertEmpty(PermissionExtends::getPermRules());

        Config::database('database', \ZN\Database\DatabaseExtends::sqlite);
    }
}