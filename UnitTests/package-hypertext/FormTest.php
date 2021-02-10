<?php namespace ZN\Hypertext;

use DB;
use Form;
use Post;
use Method;
use Buffer;
use Session;

class FormTest extends HypertextExtends
{
    public function testOpen()
    {
        $this->assertStringStartsWith('<form id="formId" name="formName" method="post">', (string) Form::open('formName', ['id' => 'formId']));
    }

    public function testOpenWithAction()
    {
        $this->assertStringContainsString('action', (string) Form::action('foo/bar')->open('formName'));
    }

    public function testOpenWithEnctype()
    {
        $this->assertStringStartsWith('<form name="upload-form" method="post" enctype="multipart/form-data">', (string) Form::enctype('multipart')->open('upload-form'));
        $this->assertStringStartsWith('<form enctype="multipart/form-data" name="upload-form" method="post">', (string) Form::open('upload-form', ['enctype' => 'multipart']));
    }

    public function testOpenWithCSRF()
    {
        $this->assertGreaterThan(0, strpos((string) Form::csrf()->open('form'), 'token'));
    }

    public function testOpenWithWhere()
    {
        $form = (string) Form::where('username', 'robot@znframework.com')->open('persons');

        $this->assertStringStartsWith("SELECT  *  FROM persons  WHERE username =  'robot@znframework.com'", DB::stringQuery());
    }

    public function testOpenWithQuery()
    {
        $form = (string) Form::query("SELECT  *  FROM persons  WHERE username =  'robot@znframework.com'")->open('persons');

        $this->assertStringStartsWith("SELECT  *  FROM persons  WHERE username =  'robot@znframework.com'", DB::stringQuery());
    }

    public function testOpenWithPrevent()
    {
        $this->assertStringStartsWith('<form name="formName" method="post" onsubmit="event.preventDefault()">', (string) Form::prevent()->open('formName'));
    }

    public function testProcessUpdate()
    {
        Buffer::callback(function()
        { 
            # Simulate
            Post::name('Haluk');
            Method::request('ValidationFormName', 'persons');
            Post::FormProcessValue(true);
            Session::insert('FormValidationRulespersons', ['name' => 
            [
                'required',
                'xss',
                'value' => 'name'
            ]]);

            echo Form::where('name', 'Haluk')->process('update')->open('persons');
            echo Form::postback()->validate('required', 'xss')->text('name');
            echo Form::close();
        });

        Form::validateErrorMessage();

        $this->assertEquals('Haluk', Form::getUpdateRow()->name);

        unset($_POST);
        unset($_REQUEST);
        Session::delete('FormValidationRulespersons');
    }

    public function testProcessInsert()
    {
        Buffer::callback(function()
        { 
            # Simulate
            Post::name('Example');
            Method::request('ValidationFormName', 'persons');
            Post::FormProcessValue(true);
            Session::insert('FormValidationRulespersons', ['name' => 
            [
                'required',
                'xss',
                'value' => 'name'
            ]]);

            echo Form::duplicateCheck()->process('insert')->open('persons');
            echo Form::postback()->validate('required', 'xss')->text('name');
            echo Form::close();
        });

        Form::validateErrorArray();

        unset($_POST);
        unset($_REQUEST);
        Session::delete('FormValidationRulespersons');
    }

    public function testProcessInvalid()
    {
        Post::namex('Example');
        Method::request('ValidationFormName', 'persons');
        Post::FormProcessValue(true);
        Session::insert('FormValidationRulespersons', ['namex' => 
        [
            'required',
            'xss',
            'value' => 'namex'
        ]]);

        try
        {
            echo Form::duplicateCheck()->process('invalid')->open('persons');
            echo Form::postback()->validate('required', 'xss')->text('namex');
            echo Form::close();
        }
        catch( \Exception $e )
        {
            $this->assertEquals('[Form::process()] method can take one of the values [update or insert].', $e->getMessage());
        }

        unset($_POST); unset($_REQUEST); Session::delete('FormValidationRulespersons');
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

    public function testSetSelectedAttribute()
    {
        $this->mock->mockSetSelectedAttribute($selected);

        $this->assertEquals('a', $selected);
    }
}