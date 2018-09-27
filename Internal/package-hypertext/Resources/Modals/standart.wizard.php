<div id="{{ $modalId }}" class="modal fade" role="dialog">
    <div class="modal-dialog {{$modalSize ?? 'modal-lg'}}">
        <div class="modal-content">
            @if( isset($modalHeader) )
            <div class="modal-header">
            @if( isset($modalDismissButton) ) 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            @endif
                <h4 class="modal-title">{{ $modalHeader }}</h4>
            </div>
            @endif

            @if( isset($modalBody) )
            <div class="modal-body">
                <p>{{ $modalBody }}</p>
            </div>
            @endif

            @if( isset($modalFooter) )
            <div class="modal-footer">
                {{ $modalFooter }}
            </div>
            @endif
        </div>
    </div>
</div>