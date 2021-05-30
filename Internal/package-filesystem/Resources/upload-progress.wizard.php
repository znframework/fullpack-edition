{[ 
    $uploadProgressFunction = 'uploadprogress' . md5(uniqid());
    $callable;
]}
<script>
function {{ $uploadProgressFunction }}(callableUploadProgress) 
{
    var formdata = new FormData();
    var request  = new XMLHttpRequest();
    var progress = callableUploadProgress;
    var files    = $('{{ $source }}')[0].files;

    for( index = 0; index < files.length; index++ )
    {   
        formdata.append('uploads', files[index]);    
    }

    request.upload.addEventListener('progress', function(e) 
    {      
        if( e.loaded <= e.total )  
        {
            var percent = Math.ceil(e.loaded / e.total * 100);

            progress(percent, e.loaded, e.total);      
        }
    });   

    request.open('post', '/echo/percent');
    request.send(formdata);
}

@selector($selector)->click(function() use($uploadProgressFunction, $callable)
{<
    {{ $uploadProgressFunction }}(function(percent, loaded, total)
    {
        {{ $callable() }}
    });
>})
</script>