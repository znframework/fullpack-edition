<?php
ZN\Inclusion\Style::use('bootstrap', 'awesome'); 
ZN\Inclusion\Script::use('jquery', 'bootstrap');
?>
<style>
code{
    background:none;
}
.pointer
{
    cursor:pointer;
}
.text-color
{
    color:#00BFFF
}
.panel-header
{
    background-color: #1b1717;
    border: 1px solid #222;
}
.panel-top-header
{
    background-color: #333;
    border:solid 1px #222;
}
.panel-text
{
    color:#ccc;
}
.h-panel-header
{
    margin-top: 15px;
    margin-bottom: 15px;
    font-size: 14px;
}
.error-block
{
    position:absolute; 
    margin-top:-19px; 
    margin-left:-10px;
    margin-right:-100px;
    width:96.37%; 
    height:20px; 
    background:white; 
    opacity:.1
}
.table-bordered>tbody>tr>td 
{
    border: 1px solid #222;
}
</style>

<div class="col-lg-12" style="z-index:1000000; margin-top:15px">
    <div class="panel panel-default panel-top-header">

        <div class="panel-heading" style="background:#222; border:none;">
            <h3 class="panel-title panel-text h-panel-header">
            <i class="fa fa-clock-o fa-fw"></i> 
            <?php echo '<span class="text-color">UNIT TEST RESULTS</span>' ?>
        </div>
        <?php echo $input; ?>
    </div>
</div>

