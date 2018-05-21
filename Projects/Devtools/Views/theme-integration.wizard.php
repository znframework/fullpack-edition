<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            {{LANG['themeIntegration']}} <small> {{LANG['overview']}}</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-exchange fa-fw"></i> {{LANG['themeIntegration']}}</h3>
            </div>
            @Form::action('integration/upload')->enctype('multipart')->class('dropzone')->open('form')
            <div class="panel-body fallback" id="fallback" style="min-height:200px">
            @Form::file('file')
            </div>
            @Form::close()
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>{{LANG['note']}}</strong>
            </div>
            <div class="panel-body">
                <p>
                {{LANG['themeBodyContent']}}
                </p>
            </div>
        </div>
    </div>

</div>

<script>
var dropzone = new Dropzone("#fallback");
</script>
