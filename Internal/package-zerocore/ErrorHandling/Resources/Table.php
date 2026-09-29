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
    display:none;
}
.source-code
{
    margin:0;
    padding:14px 0;
    border:0;
    color:#ccc;
    background:#222;
    overflow:auto;
    font-family:Consolas, monospace;
    font-size:12px;
    line-height:20px;
}
.source-line
{
    display:block;
    min-height:20px;
    padding:0 14px;
    white-space:pre;
}
.source-line.is-error
{
    color:#fff;
    background:#123d55 !important;
    border-left:4px solid #00BFFF;
    box-shadow:inset 0 1px 0 rgba(0, 191, 255, .24), inset 0 -1px 0 rgba(0, 0, 0, .28);
    padding-left:11px;
}
.source-line-number
{
    display:inline-block;
    width:52px;
    margin-right:14px;
    color:#8f8f8f;
    text-align:right;
    user-select:none;
}
.source-line.is-error .source-line-number
{
    color:#fff;
}
.source-html-tag
{
    color:#00BFFF;
}
.source-html-attribute
{
    color:#d7f5ff;
}
.source-html-value
{
    color:#f4fcff;
}
.source-html-comment
{
    color:#79aebe;
}
.source-wizard-directive
{
    color:#00BFFF;
}
.source-wizard-expression
{
    color:#e9f9ff;
}
.source-wizard-php
{
    color:#f4fcff;
}
.source-php-keyword
{
    color:#00BFFF;
}
.source-php-variable
{
    color:#d7f5ff;
}
.source-php-string
{
    color:#f4fcff;
}
.source-php-comment
{
    color:#79aebe;
}
.source-php-name
{
    color:#e9f9ff;
}
.source-php-number
{
    color:#d7f5ff;
}
.source-php-operator
{
    color:#8edfff;
}
.debug-tabs
{
    display:flex;
    align-items:flex-end;
    margin:18px 0 0;
    border-bottom:1px solid #333;
}
.debug-tabs > li
{
    margin-bottom:0;
}
.debug-tabs > li > a
{
    margin-right:4px;
    padding:10px 16px;
    color:#9fcfe2;
    background:#1e2529;
    border:1px solid #333;
    border-bottom:0;
    border-radius:4px 4px 0 0;
}
.debug-tabs > li.active > a,
.debug-tabs > li.active > a:hover,
.debug-tabs > li.active > a:focus
{
    color:#fff;
    background:#123d55;
    border:0;
    border-bottom:2px solid #00BFFF;
}
.debug-tab-content
{
    margin:0 0 15px;
    padding:18px;
    color:#ccc;
    background:#222;
    border:1px solid #333;
    border-top:0;
    min-height:126px;
}
.debug-data
{
    margin:0;
    padding:0;
    color:#d7f5ff;
    background:transparent;
    border:0;
    white-space:pre-wrap;
    word-break:break-word;
}
.debug-empty
{
    color:#79aebe;
}
.debug-suggestions
{
    margin:0;
    padding:3px 0 3px 24px;
    color:#d7f5ff;
}
.debug-suggestions li
{
    padding-left:4px;
    margin-bottom:10px;
}
.debug-data-grid
{
    display:flex;
    gap:14px;
}
.debug-data-section
{
    flex:1 1 0;
    min-width:0;
    padding:12px;
    background:#1b1b1b;
    border:1px solid #30383c;
    border-radius:3px;
}
.debug-data-title
{
    display:block;
    margin-bottom:10px;
    padding-bottom:8px;
    color:#00BFFF;
    font-size:11px;
    font-weight:600;
    letter-spacing:.4px;
    border-bottom:1px solid #30383c;
}
@media (max-width: 768px)
{
    .debug-data-grid
    {
        display:block;
    }
    .debug-data-section
    {
        margin-bottom:10px;
    }
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
                // Hata ekranı yalnızca gerçek kaynak dosyasını gösterir.
                ZN\ErrorHandling\Exceptions::display($file, $line, 0);
                ?>
            </div>
            <?php $language = $language ?? []; ?>
            <ul class="nav nav-tabs debug-tabs" role="tablist">
                <li class="active"><a href="#debugSolution" role="tab" data-toggle="tab"><i class="fa fa-lightbulb-o"></i> <?php echo $language['solution'] ?? 'Çözüm Önerisi'; ?></a></li>
                <li><a href="#debugRequest" role="tab" data-toggle="tab"><i class="fa fa-exchange"></i> <?php echo $language['request'] ?? 'İstek'; ?></a></li>
                <li><a href="#debugRuntime" role="tab" data-toggle="tab"><i class="fa fa-server"></i> <?php echo $language['runtime'] ?? 'Ortam'; ?></a></li>
            </ul>
            <div class="tab-content debug-tab-content">
                <div role="tabpanel" class="tab-pane active" id="debugSolution">
                    <ul class="debug-suggestions">
                    <?php foreach( $suggestions ?? [] as $suggestion ) { ?>
                        <li><?php echo htmlspecialchars($suggestion, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php } ?>
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane" id="debugRequest">
                    <?php $requestData = $request ?? []; ?>
                    <div class="debug-data-grid">
                        <div class="debug-data-section">
                            <span class="debug-data-title"><?php echo $language['get'] ?? 'GET'; ?></span>
                            <pre class="debug-data"><?php echo ! empty($requestData['get']) ? htmlspecialchars(ZN\ErrorHandling\Exceptions::debugJSON($requestData['get']), ENT_QUOTES, 'UTF-8') : '<span class="debug-empty">'.($language['noData'] ?? 'Veri yok.').'</span>'; ?></pre>
                        </div>
                        <div class="debug-data-section">
                            <span class="debug-data-title"><?php echo $language['post'] ?? 'POST'; ?></span>
                            <pre class="debug-data"><?php echo ! empty($requestData['post']) ? htmlspecialchars(ZN\ErrorHandling\Exceptions::debugJSON($requestData['post']), ENT_QUOTES, 'UTF-8') : '<span class="debug-empty">'.($language['noData'] ?? 'Veri yok.').'</span>'; ?></pre>
                        </div>
                        <div class="debug-data-section">
                            <span class="debug-data-title"><?php echo $language['files'] ?? 'DOSYALAR'; ?></span>
                            <pre class="debug-data"><?php echo ! empty($requestData['files']) ? htmlspecialchars(ZN\ErrorHandling\Exceptions::debugJSON($requestData['files']), ENT_QUOTES, 'UTF-8') : '<span class="debug-empty">'.($language['noData'] ?? 'Veri yok.').'</span>'; ?></pre>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="debugRuntime">
                    <pre class="debug-data"><?php echo htmlspecialchars(ZN\ErrorHandling\Exceptions::debugJSON($runtime ?? []), ENT_QUOTES, 'UTF-8'); ?></pre>
                </div>
            </div>
        </div>
    </div>
</div>
<?php defined('ZN_REDIRECT_NOEXIT') || exit;
