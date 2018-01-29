<form id="edit">
    <div class="form-group">
        Role ID
        @@Form::class('form-control')->placeholder('Role ID')->required()->text('role_id', $user->role_id):
    </div>

    <div class="form-group">
        Name
        @@Form::class('form-control')->placeholder('Name')->required()->text('name', $user->name):
    </div>

    <div class="form-group">
        Mobile
        @@Form::class('form-control')->placeholder('Mobile')->text('mobile', $user->mobile):
    </div>
    
    <div class="form-group">
        Website
        @@Form::class('form-control')->placeholder('Website')->text('website', $user->website):
    </div>

    <div class="form-group">
        About
        @@Form::class('form-control')->placeholder('About you')->textarea('about', $user->about):
    </div>
    
    @if( CURRENT_CFURI !== 'users/edit' ):
        @@Form::hidden('ajaxEdit', 1):
    @else:
        @@Form::hidden('id', URI::get('edit')):
        @@Form::onclick('ajaxEdit()')->class('btn btn-info')->button('edit', 'EDIT'):
    @endif:
</form>