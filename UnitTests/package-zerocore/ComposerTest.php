<?php namespace ZN;

class ComposerTest extends ZerocoreExtends
{
    public function testLoader()
    {
        $this->assertNull(Composer::loader(true));
        $this->assertNull(Composer::loader('vendor/autoload.php'));

        try
        {
            $this->assertNull(Composer::loader('vendor/autoload.phpx'));
        }
        catch( \Exception $e )
        {
            $this->assertEquals('Error: `vendor/autoload.phpx/vendor/autoload.php` file was not found!', $e->getMessage());
        }
    }
}