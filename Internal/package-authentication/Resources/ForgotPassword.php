
<?php $lang = ZN\Lang::default('ZN\Authentication\AuthenticationDefaultLanguage')::select('Authentication'); ?>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<table class="table table-bordered">
    <tr>
        <th colspan="2"><?php echo $lang['verificationEmail']?></th>
    </tr>
    <tr>
        <td><?php echo $lang['username']?></td>
        <td><?php echo $user; ?></td>
    </tr>
    <tr>
        <td><?php echo $lang['password']?></td>
        <td><?php echo $pass; ?></td>
    </tr>

    <tr>
        <td colspan="2">
            <a href="<?php echo $url; ?>"><?php echo $lang['learnNewPassword']; ?></a>
        </td>
    </tr>
</pre>