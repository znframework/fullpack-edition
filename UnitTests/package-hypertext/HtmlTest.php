<?php namespace ZN\Hypertext;

use Html;
use Permission;

class HtmlTest extends \PHPUnit\Framework\TestCase
{
    public function testAnchor()
    {
        $this->assertStringContainsString
        (
            '<a href="https://example.xxx">Example</a>', 
            (string) Html::anchor('https://example.xxx', 'Example')
        );

        $this->assertStringContainsString
        (
            '<a href="javascript:void(0);">Edit</a>', 
            (string) Html::anchor(':void', 'Edit')
        );
    }

    public function testButton()
    {
        $this->assertStringContainsString
        (
            '<button onclick="ajaxFunc()" type="button">Send</button>', 
            (string) Html::onclick('ajaxFunc()')->button('Send')
        );
    }

    public function testImage()
    {
        $this->assertStringContainsString
        (
            'width="200" height="200" title="" alt=""', 
            (string) Html::image('image/example.jpg', 200, 200)
        );
    }

    public function testHeading()
    {
        $this->assertStringContainsString
        (
            '<h2 id="example">Data</h2>', 
            (string) Html::heading('Data', 2, ['id' => 'example'])
        );
    }

    public function testFont()
    {
        $this->assertStringContainsString
        (
            '<font size="9" color="red" face="tahoma">Data</font>', 
            (string) Html::font('Data', 9, 'red', 'tahoma')
        );
    }

    public function testParag()
    {
        $this->assertStringContainsString
        (
            '<p style="color:red">Metin</p>', 
            (string) Html::parag('Metin', ['style' => 'color:red'])
        );
    }

    public function testStrong()
    {
        $this->assertStringContainsString
        (
            '<strong>Data</strong>', 
            (string) Html::strong('Data')
        );
    }

    public function testItalic()
    {
        $this->assertStringContainsString
        (
            '<em>Data</em>', 
            (string) Html::italic('Data')
        );
    }

    public function testUnderline()
    {
        $this->assertStringContainsString
        (
            '<u>Data</u>', 
            (string) Html::underline('Data')
        );
    }

    public function testOverline()
    {
        $this->assertStringContainsString
        (
            '<del>Data</del>', 
            (string) Html::overline('Data')
        );
    }

    public function testUndertext()
    {
        $this->assertStringContainsString
        (
            '10<sub>2</sub>', 
            '10' . Html::undertext('2')
        );
    }

    public function testOvertext()
    {
        $this->assertStringContainsString
        (
            '10<sup>2</sup>', 
            '10' . Html::overtext('2')
        );
    }

    public function testMailto()
    {
        $this->assertStringContainsString
        (
            '<a href="mailto:robot@znframework.com">Robot</a>', 
            (string) Html::mailTo('robot@znframework.com', 'Robot')
        );
    }

    public function testMailtoInvalidArgumentException()
    {
        try
        {
            Html::mailTo('robot@znframework', 'Robot');
        }
        catch( Exception\InvalidArgumentException $e )
        {
            $this->assertStringContainsString('1.($mail)', $e->getMessage());
        }
    }

    public function testScript()
    {
        $this->assertStringContainsString
        (
            'javascript', 
            (string) Html::script('abc')
        );
    }

    public function testLink()
    {
        $this->assertStringContainsString
        (
            'stylesheet', 
            (string) Html::link('abc')
        );
    }

    public function testElement()
    {
        $this->assertStringContainsString
        (
            '<b id="1">content</b>', 
            (string) Html::element('b', 'content', ['id' => 1])
        );
    }

    public function testMultiAttr()
    {
        $this->assertStringContainsString
        (
            'b', 
            (string) Html::multiAttr('b')
        );

        $this->assertStringContainsString
        (
            '<b><strong id="1"><i class="nice">b</i></strong></b>', 
            (string) Html::multiAttr('b', ['b', 'strong' => ['id' => 1], 'i' => 'class="nice"'])
        );
    }

    public function testTable()
    {
        $this->assertStringContainsString
        (
            '<table cellspacing="5" cellpadding="5" id="1" border="1" bordercolor="red" width="200" height="200" style=" color:red; border:border;">', 
            (string) Html::table()
            ->cell(5, 5)
            ->attr(['id' => 1])
            ->border(1, 'red')
            ->borderColor('red')
            ->size(200, 200)
            ->style(['color' => 'red', 'border'])
            ->create
            (
                [1, 2, 3, 4],
                ['a', 'b' => ['colspan' => 3]]
            )
        );
    }

    public function testUl()
    {
        $this->assertStringContainsString
        (
            '<ul class="example"><li>Value1</li><li>Value2</li></ul>', 
            (string) Html::class('example')->ul(function($list){
    
                echo $list->li('Value1');
                echo $list->li('Value2');
            })
        );
    }

    public function testLabel()
    {
        $this->assertStringContainsString
        (
            '<label for="checkBoxId">Do you like peas?</label>', 
            (string) Html::label('checkBoxId', 'Do you like peas?')
        );
    }

    public function testLabelThirdParameter()
    {
        $this->assertStringContainsString
        (
            '<label for="checkBoxId" form="exampleForm">', 
            (string) Html::label('checkBoxId', 'Do you like peas?', 'exampleForm')
        );
    }

    public function testMeta()
    {
        $this->assertStringContainsString
        (
            '<meta name="author" content="Ozan UYKUN" />', 
            (string) Html::meta('name:author', 'Ozan UYKUN')
        );

        $this->assertStringContainsString
        (
            '<meta http-equiv="author" content="Ozan UYKUN" />', 
            (string) Html::meta('http:author', 'Ozan UYKUN')
        );

        $this->assertStringContainsString
        (
            '<meta property="author" content="Ozan UYKUN" />', 
            (string) Html::meta('property:author', 'Ozan UYKUN')
        );

        $this->assertStringContainsString
        (
            '<meta property="author" />', 
            (string) Html::meta('property:author')
        );
    }
    
    public function testMetaArray()
    {
        $this->assertStringContainsString
        (
            '<meta name="author" content="Ozan UYKUN" />', 
            (string) Html::meta(['name:author' => 'Ozan UYKUN'])
        );
    }

    public function testContentElements()
    {
        Permission::roleId(1);

        $this->assertStringContainsString
        (
            '<article>value</article>', 
            (string) Html::perm('delete')->article('value')
        );
    }

    public function testSpace()
    {
        $this->assertStringContainsString
        (
            'Hello&nbsp;&nbsp;brother!', 
            'Hello' . Html::space(2) . 'brother!'
        );
    }

    public function testBr()
    {
        $this->assertStringContainsString
        (
            'Hello<br><br>brother!', 
            'Hello' . Html::br(2) . 'brother!'
        );
    }

    public function testHTML5Elements()
    {
        $this->assertStringContainsString
        (
            '<audio src="music.mp3" controls="controls"></audio>', 
            Html::controls()->audio('music.mp3')
        );
    }

    public function testForm()
    {
        $this->assertStringContainsString
        (
            'type="text"', 
            Html::form()->text('name')
        );
    }

    public function testList()
    {
        $this->assertStringContainsString('ul', Html::list()->create(['ul' => [1, 2, 'ol' => [1, 'a', 'b']], 'ol' => [1, 2, 3], 2, 3 => ['a', 'b', 'c'], 'd' => ['a', 'b', 'c']]));
        $this->assertStringContainsString('abc', Html::list()->create('abc'));
    }

    public function testAria()
    {
        $this->assertStringContainsString
        (
            '<b aria-ex="b">name</b>', 
            Html::aria('ex', 'b')->b('name')
        );
    }

    public function testData()
    {
        $this->assertStringContainsString
        (
            '<b data-ex="b">name</b>', 
            Html::data('ex', 'b')->b('name')
        );
    }

    public function testIcerepeating()
    {
        $this->assertStringContainsString
        (
            '<b ice:repeating="ex2">name</b>', 
            Html::iceRepeating('ex2')->b('name')
        );
    }

    public function testSpry()
    {
        $this->assertStringContainsString
        (
            '<b spry-ex="b">name</b>', 
            Html::spry('ex', 'b')->b('name')
        );
    }

    public function testSource()
    {
        $this->assertStringContainsString
        (
            '<b src="ex">name</b>', 
            Html::source('ex')->b('name')
        );
    }
}