<script>

function {{$serializerFunction}}(element)
{
    $.ajax
    ({
        url  : '{{ URL::site($serializerUrl) }}', 
        type : 'post', 
        data : $(element).closest('form').serialize(),
        success:function(data)
        {
            $('{{$serializerSelector}}').{{$serializerAttr}}(data);
        }
    });
}

</script>