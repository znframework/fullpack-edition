<?php unset($trace['params']); ?>

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
</style>

<div class="col-lg-12" style="z-index:1000000; margin-top:15px">
    <div class="panel panel-default panel-top-header">

        <div class="panel-heading" style="background:#222; border:none;">
            <h3 class="panel-title panel-text h-panel-header">
            <i class="fa fa-exclamation-triangle fa-fw"></i> 
            <?php echo '<span class="text-color">'.($type ?? 'ERROR').'</span> &raquo; ' ?>
            <?php echo $msg ?? NULL; ?></h3>
        </div>

        <div class="panel-body" style="margin-bottom:-17px;">
            <div class="list-group">
                <?php
                $i = 0;
                if( is_array($trace) ) foreach( $trace as $key => $debug )
                {
                    if
                    (   
                        is_array($debug)                          &&
                        ! empty($debug['file'])                   &&
                        ! strstr($debug['file'], DIRECTORY_INDEX) &&
                        ! strstr($debug['file'], 'Facade.php')    &&
                        ! strstr($debug['file'], 'Buffering.php') &&
                        ! strstr($debug['file'], 'ZN.php')        &&
                        ! strstr($debug['file'], 'Singleton.php') &&
                        ! strstr($debug['file'], 'Kernel.php')    &&
                        ! strstr($debug['file'], 'Wizard.php')    &&
                        ! strstr($debug['file'], 'View.php')      &&
                        ! strstr($debug['file'], 'In.php')        &&
                        ! strstr($debug['file'], 'Factory.php')   &&
                        $debug['file'] !== $file                         
                    )
                    {
                        ZN\ErrorHandling\Exceptions::display($debug['file'], $debug['line'], $i);
                        
                        $i++;
                    }  
                }
                
                ZN\ErrorHandling\Exceptions::display($file, $line, $i === 0 ? $i : count($trace));
                ?>
            </div>
        </div>
    </div>
</div>
<?php exit;