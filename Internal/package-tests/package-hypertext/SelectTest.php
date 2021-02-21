<?php namespace ZN\Hypertext;

use Form;

class SelectTest extends \PHPUnit\Framework\TestCase
{
    const options = [ '34' => 'Istanbul', '19' => 'Corum' ];

    public function testBasic()
    {
        $this->assertStringContainsString
        (
            '<option value="34">Istanbul</option>', 
            (string) Form::select('cities', self::options, '19')
        );
    }

    public function testBasicWithOptionAttr()
    {
        $this->assertStringContainsString
        (
            '<option value="34">Istanbul</option>', 
            (string) Form::option(34, 'Istanbul')->select('cities')
        );

        $this->assertStringContainsString
        (
            '<option value="34">Istanbul</option>', 
            (string) Form::option([34 => 'Istanbul'])->select('cities')
        );
    }

    public function testBasicWithSelectedValueAttr()
    {
        $this->assertStringContainsString
        (
            '<option value="34">Istanbul</option>', 
            (string) Form::selectedValue(19)->select('cities', self::options)
        );
    }

    public function testIncluding()
    {
        $this->assertStringContainsString
        (
            '<option value="19">Corum</option>', 
            (string) Form::including(['19'])->select('cities', self::options)
        );
    }

    public function testExcluding()
    {
        $this->assertStringContainsString
        (
            '<option value="34">Istanbul</option>', 
            (string) Form::excluding(['19'])->select('cities', self::options)
        );
    }

    public function testBasicWithOrdering()
    {
        $this->assertStringContainsString
        (
            '<option value="19">Corum</option>', 
            (string) Form::order('asc')->select('cities', self::options)
        );
    }

    public function testBasicWithTable()
    {
        $this->assertStringContainsString
        (
            '<select name="person">', 
            (string) Form::table('persons')->select('person', ['name' => 'name', '' => 'select name'])
        );
    }

    public function testBasicWithDBChangingValue()
    {
        $this->assertStringContainsString
        (
            '<select name="person">', 
            (string) Form::table('persons')->select('person', ['name' => function($row)
            {
                return $row->name; 
            }])
        );
    }

    public function testBasicWithTableDifferentConnection()
    {
        $this->assertStringContainsString
        (
            '<select name="person">', 
            (string) Form::table('cluster:persons')->select('person', ['name' => 'name', '' => 'select name'])
        );
    }

    public function testBasicWithQuery()
    {

        $this->assertStringContainsString
        (
            '<select name="person">', 
            (string) Form::query('select * from persons')->select('person', ['name' => 'name', '' => 'select name'])
        );
    }

    public function testBasicMultiSelect()
    {
        $this->assertStringContainsString
        (
            '<select multiple="multiple" name="cities">', 
            (string) Form::multiselect('cities', self::options, '19')
        );
    }

    public function testBasicMultiSelectJsonKeys()
    {
        $this->assertStringContainsString
        (
            '<select multiple="multiple" name="cities">', 
            (string) Form::multiselect('cities', self::options, json_encode(['19', '34']))
        );
    }
    
    public function testBasicMultiSelectMultipleKeys()
    {
        $this->assertStringContainsString
        (
            '<select multiple="multiple" name="cities">', 
            (string) Form::multiselect('cities', self::options, ['19', '34'])
        );
    }

    
}