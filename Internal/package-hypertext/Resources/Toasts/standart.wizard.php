<div id="{{ $toastId }}" class="toast" data-autohide="{{ $toastAutoHide }}">
  <div class="toast-header">
    {{ $toastHeader }}

    @if( ! empty($toastDismissButton) ) 
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
    @endif
  </div>
  <div class="toast-body">
  {{ $toastBody }}
  </div>
</div>