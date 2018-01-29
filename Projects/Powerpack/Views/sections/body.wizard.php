
@Import::view('sections/header'):

<!-- MAIN -->
<div class="main">
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container-fluid">
  
        @status($success ?? Redirect::select('success') ?: NULL):
        @status($error   ?? Redirect::select('error')   ?: NULL, 'danger'):

        @$view:
        </div>
    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN -->


@Import::view('sections/footer'):

@Import::view(CURRENT_CONTROLLER . '/script.php'):