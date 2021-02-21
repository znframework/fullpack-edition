<?php namespace ZN\XML;

class LoaderTest extends \ZN\Test\GlobalExtends
{
    const xml = self::default . 'package-xml/resources/example.xml';

    public function testLoad()
    {
        $loader = new Loader;

        $this->assertIsString($loader->do(self::xml));
    } 

    public function testLoadFileNotFoundException()
    {
        $loader = new Loader;

        try
        {
            $loader->do('unknown');
        }
        catch( Exception\FileNotFoundException $e )
        {
            $this->assertStringContainsString('unknown.xml', $e->getMessage());
        }
        
    }
}