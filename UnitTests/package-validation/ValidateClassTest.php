<?php namespace ZN\Validation;

use Validate;

class ValidateClassTest extends \ZN\Test\GlobalExtends
{
    public function testValid()
    {
        $data = 'abc';

        $this->assertTrue(Validate::data($data)->minchar(3)->maxchar(5)->get());
    }  

    public function testInvalid()
    {
        $data = 'abc';

        $this->assertFalse(Validate::data($data)->minchar(5)->maxchar(16)->get());
    } 

    public function testStatus()
    {
        $data = 'abc';

        Validate::data($data)->minchar(5)->maxchar(16)->get();

        $this->assertEquals(['minchar' => false, 'maxchar' => true], Validate::status());
    } 
}