<?php namespace ZN\Validation;

class PHPTest extends \ZN\Test\GlobalExtends
{
    public function testMake()
    {
        $this->assertEquals('&#60;&#63;php echo 1;', Validator::php('<?php echo 1;'));
    }  
}