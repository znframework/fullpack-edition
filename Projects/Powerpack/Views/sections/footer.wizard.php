    
    <div class="clearfix"></div>

    <footer>
        <div class="container-fluid">
            <p class="copyright">ZN Powerpack &copy; 2017 <a href="https://www.themeineed.com" target="_blank">ZN Framework</a>. All Rights Reserved.</p>
        </div>
    </footer>
</div>
<!-- END WRAPPER -->
<!-- Javascript -->
<script src="{{THEMES_URL}}vendor/jquery/jquery.min.js"></script>
<script src="{{THEMES_URL}}vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="{{THEMES_URL}}vendor/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{THEMES_URL}}vendor/jquery.easy-pie-chart/jquery.easypiechart.min.js"></script>
<script src="{{THEMES_URL}}vendor/chartist/js/chartist.min.js"></script>
<script src="{{THEMES_URL}}vendor/toastr/toastr.min.js"></script>
<script src="{{THEMES_URL}}scripts/klorofil-common.js"></script>

@foreach( $scripts ?? [] as $script ):
    <script src="{{$script}}"></script>
@endforeach:

{{$modalbox ?? NULL}}

<script>
$(document).ajaxSend(function(e, jqXHR)
{
  $('#loading').removeClass('hide');
});

$(document).ajaxComplete(function(e, jqXHR)
{
  $('#loading').addClass('hide');
});

function ajaxSearch(object, event)
{
    if( event.which === 13 )
    {
        $.ajax
        ({
            type   : 'post',
            data   : {'searchData' : $(object).val()},
            success: function(data)
            {
                document.documentElement.innerHTML = data;
            }
        });
    }
}
</script>