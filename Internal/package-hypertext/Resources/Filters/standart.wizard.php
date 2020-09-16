<script>
$(document).ready(function(){
  $("{{ $filterSource }}").on("{{ $filterEvent ?? 'keyup' }}", function() {
    var value = $(this).val().toLowerCase();
    $("{{ $filterTarget }}").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>