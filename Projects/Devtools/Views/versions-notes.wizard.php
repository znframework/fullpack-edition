<!-- Page Heading -->
<div class="row">
    <div class="col-lg-11">
        <h1 class="page-header">
            {{LANG['versionNotes']}} <small> {{LANG['overview']}}</small>
        </h1>

    </div>

    <div class="col-lg-1">
        <h1 class="page-header">
        @Form::open()
            {{Form::class('btn btn-info')->submit('refreshNotes', LANG['refreshButton'])}}
        @Form::close()
        </h1>
    </div>
</div>
<div class="row">

    <div class="col-lg-12">

        @if( ! empty($znframework) ) foreach( $znframework as $key => $version )
            {[
                if( $key === 10 || is_string($version) )
                {
                    break;
                }

                if( ! Session::select($version->name) )
                {
                    $detail = Restful::useragent(true)->get($version->commit->url);

                    Session::insert($version->name, $detail);
                }
                else
                {
                    $detail = Session::select($version->name);
                }  
                 
            ]}
            @if( $detail->commit ?? NULL )
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 style="cursor:pointer" data-toggle="collapse" data-target="/#id{{$key}}" class="panel-title">
                    <i class="fa fa-book fa-fw"></i>
                    {{$version->name}}
                    <span><i class="fa fa-angle-down fa-fw"></i></span>
                </h3>
            </div>
            <div id="id{{$key}}" class="collapse panel-body">
                <div class="list-group">
                    {{$detail->commit->message}}
                    <p>
                    <a target="_blank" href="{{$detail->html_url}}">&raquo; @LANG['detail']:</a>
                    </p>
                </div>
            </div>
        </div>
            @endif
        @endforeach
  
    </div>

</div>

@Form::close()

@plugin(array
(
	'Dashboard/highlight/styles/agate.css',
	'Dashboard/highlight/highlight.pack.js'
))

<script>hljs.initHighlightingOnLoad();</script>
