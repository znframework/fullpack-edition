<?php namespace ZN;

class ComposerTest extends ZerocoreExtends
{
    public function testLoader()
    {
        $this->assertNull(Composer::loader(true));
        $this->assertNull(Composer::loader('Internal/package-composer/autoload.php'));

        try
        {
            $this->assertNull(Composer::loader('Internal/package-composer/autoload.phpx'));
        }
        catch( \Exception $e )
        {
            $this->assertEquals('Error: `Internal/package-composer/autoload.phpx/vendor/autoload.php` file was not found!', $e->getMessage());
        }
    }
}