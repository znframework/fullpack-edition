<?php namespace ZN;

class ClassesTest extends ZerocoreExtends
{
    public function testClass()
    {
        $this->assertEquals('zn\cache\cacheextends', Classes::class('CacheExtends'));
        $this->assertStringContainsString('UnitTests/extends/resources/InternalExampleClass.php', Classes::class('ExampleClass'));
        $this->assertEquals('zn\classestest', Classes::class('zn\classestest'));
    }

    public function testReflection()
    {
        $this->assertIsObject(Classes::reflection('ZN\Database\DB'));
    }

    public function testIsRelation()
    {
        try
        {
            Classes::isRelation('ZN\Database\DB', 'x');
        }
        catch( \Exception $e )
        {
            $this->assertEquals('`2.($object)` parameter should contain the object data type!', $e->getMessage());
        }

        $db = new Database\DB;

        $this->assertTrue(Classes::isRelation('ZN\Database\DB', $db));
    }

    public function testIsParent()
    {
        $db = new Database\DB;

        $this->assertTrue(Classes::isParent('ZN\Database\Connection', $db));
    }

    public function testMethodExists()
    {
        $this->assertTrue(Classes::methodExists('ZN\Database\DB', 'get'));
    }

    public function testPropertyExists()
    {
        $this->assertTrue(Classes::methodExists('ZN\Database\DB', 'table'));
    }

    public function testVars()
    {
        $this->assertIsArray(Classes::vars('ZN\Database\DB'));
    }

    public function testName()
    {
        $db = new Database\DB;

        $this->assertEquals('ZN\Database\DB', Classes::name($db));
        $this->assertEquals('', Classes::name('xyz'));
    }

    public function testDeclared()
    {
        $this->assertIsArray(Classes::declared());
    }

    public function testDeclaredInterfaces()
    {
        $this->assertIsArray(Classes::declaredInterfaces());
    }

    public function testDeclaredTraits()
    {
        $this->assertIsArray(Classes::declaredTraits());
    }
}