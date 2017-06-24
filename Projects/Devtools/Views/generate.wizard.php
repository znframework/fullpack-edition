@@Form::open():

<div class="row">
    <div class="col-lg-11">
        <h1 class="page-header">
            @@Strings::titleCase(CURRENT_CFUNCTION): <small> {{LANG['overview']}}</small>
        </h1>

    </div>

    <div class="col-lg-1">
        <h1 class="page-header">
            @@Form::class('btn btn-info')->submit('generate', LANG['generateButton']):
        </h1>
    </div>
</div>

@Import::view($content . '.wizard'):

@@Form::close():

@if( ! empty($files) ):

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list fa-fw"></i> {{LANG[($title ?? $content . 's')]}}</h3>
            </div>
            <div class="panel-body">
                <div class="list-group">

                    @foreach( $files as $key => $file ):

                    <a href="/#b@$key:" class="list-group-item" data-toggle="collapse">
                        <i class="fa fa-fw fa-file-text-o"></i>
                        {[$relativePath = absoluteRelativePath($file)]}
                        {{Form::id('renameId' . $key)->style('width:20%; background:none; border:none;')->class('text')->text('rename', $relativePath)}}
                        <span><i class="fa fa-angle-down fa-fw"></i></span>

                        @if( $file !== 'Projects/Projects.php' ):
                        <span class="pull-right"><i onclick="deleteProcess('generate/deleteFile/{{$relativePath}}');" class="fa fa-trash-o fa-fw"></i></span>
                        <span class="pull-right"><i onclick="renameProcess('{{$relativePath}}', '/#renameId{{$key}}');" title="{{LANG['renameFile']}}" class="fa fa-edit fa-fw"></i></span>
                        @endif:
                    </a>

                    <pre id="b@$key:" class="collapse"><div style="width/:100%; height/:800px;" id="editor{{$key}}" onkeyup="saveProcess('{{absoluteRelativePath($file)}}', this, event, {{$key}});" contenteditable="true">@@Security::phpTagEncode(Security::htmlEncode(File::read($relativePath))):</div></pre>
                    <script>
                        var editor = ace.edit("editor{{$key}}");
                        editor.setTheme("ace/theme/{{SELECT_EDITOR_THEME}}");
                        editor.getSession().setMode("ace/mode/php");
                    </script>

                    @endforeach:
                </div>
            </div>
        </div>
    </div>
</div>
@endif:

<script>

function renameProcess(oldname, newname)
{
    if( confirm("@@LANG['areYouSure']:") )
    {
        $.ajax
        ({
            'url'/:'@@siteUrl('generate/renameFile'):',
            'data'/:'old=' + oldname + '&new=' + $(newname).val() + '&current={{CURRENT_CFUNCTION}}',
            'type'/:'post',
            'success'/:function()
            {
                window.location.reload();
            }
        });
    }
}

function saveProcess(link, e, evt, key)
{
    var editor = ace.edit("editor" + key);
    var code   = editor.getValue();

    $.ajax
    ({
        'url'/:'@@siteUrl('generate/saveFile'):',
        'data'/:'link=' + link + '&content=' + encodeURIComponent(code),
        'type'/:'post',
        'success'/:function(data)
        {

        }
    });
}

</script>
