<?php namespace ZN\Hypertext;

use Form;

class SelectTest extends \PHPUnit\Framework\TestCase
{
    public function testSelect()
    {
        $options = [ '34' => 'Istanbul', '19' => 'Corum' ];

        $this->assertStringContainsString
        (
            '<option value="34">Istanbul</option>', 
            (string) Form::select('cities', $options, '19')
        );

        $this->assertStringContainsString
        (
            '<option value="19">Corum</option>', 
            (string) Form::including(['19'])->select('cities', $options)
        );

        $this->assertStringContainsString
        (
            '<option value="34">Istanbul</option>', 
            (string) Form::excluding(['19'])->select('cities', $options)
        );

        $this->assertStringContainsString
        (
            '<option value="19">Corum</option>', 
            (string) Form::order('asc')->select('cities', $options)
        );

        $this->assertStringContainsString
        (
            '<select name="person">', 
            (string) Form::table('persons')->select('person', ['name' => 'name', '' => 'select name'])
        );

        $this->assertStringContainsString
        (
            '<select name="person">', 
            (string) Form::query('select * from persons')->select('person', ['name' => 'name', '' => 'select name'])
        );
    }

    public function testMultiSelect()
    {
        $options = [ '34' => 'Istanbul', '19' => 'Corum' ];

        $this->assertStringContainsString
        (
            '<select multiple="multiple" name="cities">', 
            (string) Form::multiselect('cities', $options, ['19', '34'])
        );

        $this->assertStringContainsString
        (
            '<option value="19">Corum</option>', 
            (string) Form::including(['19'])->multiselect('cities', $options)
        );

        $this->assertStringContainsString
        (
            '<option value="34">Istanbul</option>', 
            (string) Form::excluding(['19'])->multiselect('cities', $options)
        );

        $this->assertStringContainsString
        (
            '<select multiple="multiple" name="cities">', 
            (string) Form::multiselect('cities', $options, json_encode(['19', '34']))
        );
    }
}