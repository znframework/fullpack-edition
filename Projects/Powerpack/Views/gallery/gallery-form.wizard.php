@@Form::enctype('multipart')->open('uploadForm'):
    <div class="form-group">
        @@Form::class('form-control')->table('gallery_categories')->select('categoryId', ['id' => 'name', 0 => 'Select Category']):
    </div>

    <div class="form-group">
        @@Form::file('upload', true):
    </div>

    @@Form::class('btn btn-info')->submit('uploadSubmit', $dict->uploadButton):
@@Form::close():