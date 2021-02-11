<?php namespace ZN;

class AutoloaderTest extends ZerocoreExtends
{
    public function testBuild()
    {
        $this->assertNull(Autoloader::restart());
    }   

    public function testCreateClassMap()
    {
        Config::autoloader('directoryScanning', false);

        $this->assertFalse(Autoloader::createClassMap());

        Config::autoloader('directoryScanning', true);
    }

    public function testGetClassFileInfo()
    {
        $this->assertEquals
        (
            [
                'path'      => '',
                'class'     => 'ZN\Database\DB',
                'namespace' => ''
            ],
            Autoloader::getClassFileInfo('ZN\Database\DB')
        );
    }

    public function testTokenClassFileInfo()
    {
        $this->assertEmpty(Autoloader::tokenClassFileInfo(self::resources . 'map'));

        $this->assertEquals
        (
            ['namespace' => 'ZN\Database', 'class' => 'DB'],
            Autoloader::tokenClassFileInfo('Internal/package-database/DB.php')
        );
    }
    
    public function testTokenFileInfo()
    {
        $this->assertFalse(Autoloader::tokenFileInfo(self::resources . 'map'));
    }

    public function testRegister()
    {
        $this->assertNull(Autoloader::register('run'));
    }

    public function testCreateClassMapTopOutput()
    {
        $this->autoloaderMock->mockCreateClassMapTopOutput($output);

        $this->assertStringContainsString('This file automatically created and updated', $output);
    }

    public function testAliases()
    {
        $this->autoloaderMock->mockAliases();
    }

    public function testGetFacadeContent()
    {
        $this->assertStringContainsString('const target', $this->autoloaderMock->mockGetFacadeContent());
    }

    public function testGetClassNamespace()
    {
        $facade = 'Example\Class';

        $this->autoloaderMock->mockGetClassNamespace($facade, $namespace);

        $this->assertEquals('Class', $facade);
        $this->assertEquals(' namespace Example;', $namespace);
    }
}