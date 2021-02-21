<?php namespace ZN\Security;

use Security;

class PHPTagTest extends \PHPUnit\Framework\TestCase
{
    public function testPhpEncode()
    {
        $this->assertEquals('&#60;&#63;php echo 1;', Security::phpTagEncode('<?php echo 1;'));
    }

    public function testPhpDecode()
    {
        $this->assertEquals('<?php echo 1;', Security::phpTagDecode('&#60;&#63;php echo 1;'));
    }

    public function testPhpClean()
    {
        $this->assertEquals(' echo 1;', Security::phpTagClean('<?php echo 1;'));
    }
}