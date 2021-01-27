<?php $lang = ZN\Lang::default('ZN\Authentication\AuthenticationDefaultLanguage')::select('Authentication'); ?>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<table class="table table-bordered">
    <tr>
        <th><?php echo $lang['activationProcess']?></th>
    </tr>

    <tr>
        <td>
            <a href="<?php echo $url.'user/'.$user.'/pass/'.$pass; ?>"> <?php echo $lang['activation']?> </a>
        </td>
    </tr>
</table>

