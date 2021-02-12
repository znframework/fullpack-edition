<?php $lang = ZN\Lang::default('ZN\CoreDefaultLanguage')::select('IndividualStructures'); ?>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
    border: 1px solid #222; color : #ccc; font-size:14px;
}
</style>

<div class="col-lg-12" style="z-index:1000000; margin-top:15px">
    <div class="panel panel-default panel-top-header">

        <div class="panel-heading" style="background:#222; border:none;">
            <h3 class="panel-title panel-text h-panel-header">
            <i class="fa fa-clock-o fa-fw"></i> 
            <?php echo '<span class="text-color">BENCHMARK</span>' ?>
            <?php echo $message ?? NULL; ?></h3>
        </div>
        <a href="#openBenchmark<?php echo $key?>" class="list-group-item panel-header" data-toggle="collapse">
            <span><i class="fa fa-angle-down fa-fw panel-text"></i>&nbsp;&nbsp;&nbsp;&nbsp; Benchmark Result</span>
        </a>
        <div class="panel-body collapse in" id="openBenchmark" style="margin-bottom:-17px;">
            <div class="list-group panel-text">
                <table class="table table-bordered">
                    <tr><td width="20%"><?php echo $lang['benchmark:elapsedTime']; ?></td><td><?php echo $elapsedTime." ".$lang['benchmark:second']; ?></td></tr>
                    <tr><td><?php echo $lang['benchmark:memoryUsage']; ?></td><td><?php echo $memoryUsage." ".$lang['benchmark:byte']; ?></td></tr>
                    <tr><td><?php echo $lang['benchmark:maxMemoryUsage']; ?></td><td><?php echo $maxMemoryUsage." ".$lang['benchmark:byte']; ?></td></tr>
                    <tr><td><?php echo $lang['benchmark:countFile']; ?></td><td><?php echo count(get_required_files()); ?></td></tr>
                </table>
            </div>
        </div>

        <a href="#openServerData" class="list-group-item panel-header" data-toggle="collapse">
            <span><i class="fa fa-angle-down fa-fw panel-text"></i>&nbsp;&nbsp;&nbsp;&nbsp; Server Request Data</span>
        </a>
        <div class="panel-body collapse" id="openServerData" style="margin-bottom:-17px;">
            <div class="list-group panel-text">
                <table class="table table-bordered">
                    <?php foreach( $_SERVER as $key => $value ): ?>
                    <tr><td width="20%" class=><?php echo $key ?? NULL ?></td><td><?php echo $value ?? NULL ?></td></tr>
                    <?php endforeach; ?>
                
                </table>
            </div>
        </div>
    </div>
</div>

