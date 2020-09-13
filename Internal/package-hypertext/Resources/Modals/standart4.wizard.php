<div id="{{ $modalId }}" class="modal">
    <div class="modal-dialog {{ZN\Base::prefix($modalSize, 'modal-')}}">
        <div class="modal-content">
            @if( ! empty($modalHeader) )
            <div class="modal-header"> 
                <h4 class="modal-title">{{ $modalHeader }}</h4>
                @if( ! empty($modalDismissButton) ) 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                @endif
            </div>
            @endif

            @if( ! empty($modalBody) )
            <div class="modal-body">
                {{ $modalBody }}
            </div>
            @endif

            @if( ! empty($modalFooter) )
            <div class="modal-footer">
                {{ $modalFooter }}
            </div>
            @endif
        </div>
    </div>
</div>