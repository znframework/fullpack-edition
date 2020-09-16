<div class="media border {{ $mediaObjectPadding ?? 'p-3' }}">
    <img src="{{ URL::base($mediaObjectAvatar) }}" alt="{{ $mediaObjectName }}" class="{{ $mediaObjectAvatarMargin ?? 'mr-3 mt-2' }} rounded-{{ $mediObjectAvatarType ?? 'circle' }} " style="width:{{ $mediaObjectAvatarSize ?? 60 }}px;">
    <div class="media-body">
        <h4>{{ $mediaObjectName }} <small><i>{{ $mediaObjectDate }}</i></small></h4>
        <p>{{ $mediaObjectContent }}</p>  

        @if( $mediaObjectAnswer )
            {{ $mediaObjectAnswer }}
        @endif    
    </div>
</div>