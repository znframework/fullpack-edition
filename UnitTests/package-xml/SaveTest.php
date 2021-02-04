<?php namespace ZN\XML;

class SaveTest extends \ZN\Test\GlobalExtends
{
    const xml = self::default . 'package-xml/resources/example.xml';

    public function testSave()
    {
        $loader = new Loader;

        $xml = $loader->do(self::xml);

        $save = new Save;

        $this->assertTrue($save->do(self::xml, $xml));
    } 
}