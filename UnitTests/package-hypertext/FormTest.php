<?php namespace ZN\Hypertext;

use DB;
use Form;
use Post;
use Buffer;
use Session;

class FormTest extends \PHPUnit\Framework\TestCase
{
    public function testOpen()
    {
        $this->assertStringStartsWith('<form id="formId" name="formName" method="post" enctype="multipart/form-data">', (string) Form::enctype('multipart')->open('formName', ['id' => 'formId']));
        $this->assertStringStartsWith('<form name="upload-form" method="post" enctype="multipart/form-data">', (string) Form::enctype('multipart')->open('upload-form'));
        $this->assertGreaterThan(0, strpos((string) Form::csrf()->open('form'), 'token'));

        $form = (string) Form::where('username', 'robot@znframework.com')->open('persons');

        $this->assertStringStartsWith("SELECT  *  FROM persons  WHERE username =  'robot@znframework.com'", DB::stringQuery());

        $form = (string) Form::query("SELECT  *  FROM persons  WHERE username =  'robot@znframework.com'")->open('persons');

        $this->assertStringStartsWith("SELECT  *  FROM persons  WHERE username =  'robot@znframework.com'", DB::stringQuery());
        $this->assertStringStartsWith('<form name="formName" method="post" onsubmit="event.preventDefault()">', (string) Form::prevent()->open('formName'));
    }

    public function testProcess()
    {
        Buffer::callback(function()
        { 
            Post::FormProcessValue(true); Post::name('abc');

            Form::process('insert')->open('person')->validate('required')->text('name')->close(); 
        
            $this->assertIsString(Form::validateErrorMessage());

            unset($_POST);

            Post::FormProcessValue(true); Post::name('abc');

            Buffer::callback(function()
            { 
                Form::process('insert')->whereColumn('name')->whereValue('abc')->open('persons')->validate('required')->text('name')->close(); 
            });

            $this->assertIsArray(Form::validateErrorArray());

            unset($_POST);
        });
    }

    public function testClose()
    {
        $this->assertStringContainsString('</form>', (string) Form::close());
    }

    public function testCallHTML5Element()
    {
        $this->assertStringContainsString
        (
            '<input type="color" name="myColor" value="">', 
            (string) Form::color('myColor')
        );
    }

    public function testToString()
    {
        $this->assertStringContainsString
        (
            '<input type="color" name="myColor" value="">', 
            (string) Form::open()->color('myColor')->close()
        );
    }

    public function testPostback()
    {
        $_POST['myColor'] = 'My Color';

        $this->assertStringContainsString
        (
            '<input type="color" name="myColor" value="My Color">', 
            (string) Form::postback()->color('myColor')
        );
    }

    public function testValidate()
    {
        $form = (string) Form::open('validationForm')->validate('required')->text('name')->close();

        $rules = Session::select('FormValidationRulesvalidationForm');

        $this->assertSame(['name' => ['required', 'value' => 'name']], $rules);
    }

    public function testVMethods()
    {
        $this->assertStringContainsString
        (
            'pattern="^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$"', 
            (string) Form::vEmail()->vMaxchar(100)->text('email')
        );

        $this->assertStringContainsString('minlength="3"', (string) Form::vMinchar(3)->text('name'));
        $this->assertStringContainsString('pattern="^[a-zA-Z]+$"', (string) Form::vAlpha()->text('name'));
        $this->assertStringContainsString('***-***-**-**', (string) Form::vPhone('***-***-**-**')->text('phone'));
    }

    public function testSerializer()
    {
        $this->assertStringContainsString
        (
            'serializer', 
            Buffer::callback(function(){ Form::serializer('Contact/ajaxSendForm', '#successDiv')->button('send', 'SEND');})
        );
    }

    public function testTrigger()
    {
        $this->assertStringContainsString
        (
            'trigger', 
            Buffer::callback(function(){ Form::trigger('keyup', 'Validations/control', function(){})->button('send', 'SEND');})
        );
    }

    public function testGetUpdateRow()
    {
        $this->assertNull
        (
            Form::getUpdateRow()
        );
    }

    public function testCloseJSValidationFunction()
    {
        $this->assertIsString
        (
            Buffer::callback(function(){  Form::open()->vBetween(10, 20)->close();})
        );
    }

    public function testDatetimeLocal()
    {
        $this->assertIsString
        (
            (string) Form::datetimeLocal()
        );
    }
}