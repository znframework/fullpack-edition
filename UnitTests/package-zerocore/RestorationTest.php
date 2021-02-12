<?php namespace ZN;

class RestorationTest extends ZerocoreExtends
{
    public function testRouteURI()
    {
        $this->assertNull(Restoration::routeURI(['1', '2'], 'example'));
    }

    public function testIsMachinesIP()
    {
        $this->assertFalse(Restoration::isMachinesIP());

        define('PROJECT_MODE', 'restoration');

        $this->assertTrue(Restoration::isMachinesIP());

        Config::project('restoration', ['machinesIP' => '127.0.0.1']);

        $this->assertTrue(Restoration::isMachinesIP());

        Config::project('restoration', ['machinesIP' => '127.0.0.2']);

        $this->assertFalse(Restoration::isMachinesIP());
    }

    public function testMode()
    {
        $this->assertFalse(Restoration::mode(['machinesIP' => '127.0.0.1']));

        $this->assertNull(Restoration::mode(['machinesIP' => '127.0.0.2']));

        $this->assertNull(Restoration::mode(['machinesIP' => '127.0.0.2', 'functions' => ['all', 'main' => 'x', 'main']]));

        $this->assertNull(\Restoration::mode(['machinesIP' => '127.0.0.2']));
    }

    public function testStart()
    {
        $this->assertTrue(Restoration::start('Frontend', 'full'));
        $this->assertTrue(Restoration::endDelete('Frontend'));
    }
}