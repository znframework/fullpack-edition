<script>
function {{$serializerFunction}}(element)
{
    var form = $(element).closest('form');

    if ( form.is(":valid") ) 
    {
        $.ajax
        ({
            url  : '{{ URL::site($serializerUrl) }}', 
            type : 'post', 
            data : form.serialize(),
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
}
</script>