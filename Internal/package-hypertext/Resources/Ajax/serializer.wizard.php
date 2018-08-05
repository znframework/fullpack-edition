<script>

function {{$serializerFunction}}(element)
{
    $.ajax
    ({
        url  : '{{ URL::site($serializerUrl) }}', 
        type : 'post', 
        data : $(element).closest('form').serialize(),
        {{ $serializerProperties}}
        success:function(data)
        {
            @if( is_string($serializerSelector) )
            $('{{$serializerSelector}}').html(data);
            @elseif( is_callable($serializerSelector) )
            {{ $serializerSelector() }}
            @endif
        }
    });
}

</script>