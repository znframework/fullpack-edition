<script>
function {{$serializerFunction}}(element)
{
    var form = $(element).closest('form');

    if ( form.is(":valid") ) 
    {
        var value = $(element).val();
        var name  = $(element).attr('name');

        $.ajax
        ({
            url  : '{{ URL::site($serializerUrl) }}', 
            type : 'post', 
            data : {name : value},
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