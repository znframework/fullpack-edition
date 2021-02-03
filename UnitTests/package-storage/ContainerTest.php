<?php namespace ZN\Storage;

class ContainerTest extends StorageExtends
{
    public function testContainer()
    {
        $session = new Session;

        unset($_SESSION);

        $session->start();

        $session->regenerate();

        $storage = new Storage(new Session);

        $storage->insert('example', 'Example');

        $this->assertEquals('Example', $storage->select('example'));
    }
}