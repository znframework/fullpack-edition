@@Form::id('createForm')->open('createForm'):
    <div class="form-group">
        @@Form::placeholder($dict->email)->class('form-control')->email('email'):
    </div>

    <div class="form-group">
        @@Form::placeholder($dict->password)->class('form-control')->password('password'):
    </div>

    <div class="form-group">
        @@Form::placeholder($dict->passwordAgain)->class('form-control')->password('passwordAgain'):
    </div>

    @@Form::class('btn btn-info')->onclick('ajaxForm(\'createForm\')')->button('createButton', $dict->createButton):
@@Form::close():