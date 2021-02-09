<?php namespace ZN\Buffering;

class FileTest extends \PHPUnit\Framework\TestCase
{
    public function testDo()
    {
        $file = new File;

        $this->assertIsString($file->do('robots.txt'));
    }

    public function testDoException()
    {
        $file = new File;

        try
        {
            $this->assertIsString($file->do('unknown'));
        }
        catch( \Exception $e )
        {
            $this->assertIsString($e->getMessage());
        }
        
    }
}