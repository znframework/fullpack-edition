<?php namespace ZN\XML;

class ParserTest extends \ZN\Test\GlobalExtends
{
    const xml = self::default . 'package-xml/resources/example.xml';

    public function testParse()
    {
        $parser = new Parser;
        $loader = new Loader;

        $xml = $loader->do(self::xml);

        $output = $parser->do($xml);

        $this->assertEquals('media', $output->name);
    } 

    public function testParseObject()
    {
        $parser = new Parser;
        $loader = new Loader;

        $xml = $loader->do(self::xml);

        $output = $parser->object($xml);

        $this->assertEquals('media', $output->name);
    }  

    public function testParseArray()
    {
        $parser = new Parser;
        $loader = new Loader;

        $xml = $loader->do(self::xml);

        $output = $parser->array($xml);

        $this->assertEquals('media', $output['name']);
    }  

    public function testParseJson()
    {
        $parser = new Parser;
        $loader = new Loader;

        $xml = $loader->do(self::xml);

        $output = $parser->json($xml);

        $this->assertEquals('media', json_decode($output)->name);
    }  

    public function testParseURL()
    {
        $parser = new Parser;

        $output = $parser->simpleURL('https://www.w3schools.com/xml/note.xml');

        $this->assertIsObject($output);
    }
}