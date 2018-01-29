<script>

function ajaxForm(id)
{
    $.ajax
    ({
        type   : 'post',
        data   : $('#' + id).serialize(),
        success: function(data)
        {
            document.documentElement.innerHTML = data;
        }
    });
}

function ajaxEdit()
{
    $.ajax
    ({
        type   : 'post',
        data   : $('#edit').serialize(),
        success: function(data)
        {
            document.documentElement.innerHTML = data;
        }
    });
}

function previewImageprofile(no)
{
	var oFReader = new FileReader();

	oFReader.readAsDataURL(document.getElementById("img" + no).files[0]);

	oFReader.onload = function (oFREvent)
	{
		document.getElementById("uploadPreviewprofile" + no).src = oFREvent.target.result;

        $('#upload').submit();
	};
}

</script>