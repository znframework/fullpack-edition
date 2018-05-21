<div class="hide" id="loadingDiv"></div>
<div id="wrapper">

@view('top.wizard')

<div id="page-wrapper">
<div class="container-fluid">

@if( isset($page) )
    @view(ZN\Base::suffix($page, '.wizard'), $pdata ?? NULL)
@else
    <br>
@endif

@if( ($success ?? NULL) || $success = Redirect::selectData('success') )

    {[Redirect::deleteData('success')]}
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="fa fa-info-circle"></i> {{$success}}
            </div>
        </div>
    </div>
@endif

@if( $error ?? NULL )
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="fa fa-info-circle"></i> {{$error}}
            </div>
        </div>
    </div>
@endif


</div>
</div>

<div class="container-fluid">
  <p class="text-muted text-right" style="margin-top:12px">ZN Devtools © 2018 ZN Framework, All Rights Reserved</p>
</div>

</div>

<script>

$(document).ajaxSend(function(e, jqXHR)
{
  $('#loadingDiv').removeClass('hide');
});

$(document).ajaxComplete(function(e, jqXHR)
{
  $('#loadingDiv').addClass('hide');
});

function deleteProcess(link)
{
    if( confirm('{{LANG['areYouSure']}}') )
    {
        window.location =  '{{URL::site()}}' + link;

        return false;
    }
}
</script>
