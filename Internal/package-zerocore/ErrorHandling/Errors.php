<?php namespace ZN\ErrorHandling;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Lang;
use ZN\Request;

class Errors
{
    /**
     * Outputs the browser-side AJAX error screen handler at the end of a page.
     *
     * @return void
     */
    public static function ajaxHandler()
    {
        if( Request::isAjax() )
        {
            return;
        }

        echo <<<'JS'
<script>
window.ZNErrorHandling=window.ZNErrorHandling||{};window.ZNErrorHandling.showAjaxError=function(xhr){var response=xhr.responseText;try{var debug=JSON.parse(response);response=debug.debug&&debug.debug.html?debug.debug.html:response}catch(e){}if(!response)return;var modal=document.getElementById('znAjaxErrorModal');if(!modal){modal=document.createElement('div');modal.id='znAjaxErrorModal';modal.style.cssText='position:fixed;z-index:2147483647;inset:0;display:none;padding:24px;background:rgba(0,0,0,.62);';modal.innerHTML='<div style="height:100%;max-width:1400px;margin:auto;background:#fff;border-radius:6px;overflow:hidden;box-shadow:0 12px 38px rgba(0,0,0,.45);"><button type="button" style="position:absolute;right:38px;top:34px;z-index:1;border:0;border-radius:4px;padding:7px 14px;color:#fff;background:#00a8e8;cursor:pointer">Kapat</button><iframe sandbox="allow-scripts" style="width:100%;height:100%;border:0"></iframe></div>';document.body.appendChild(modal);modal.querySelector('button').onclick=function(){modal.style.display='none'}}modal.querySelector('iframe').srcdoc=response;modal.style.display='block'};window.ZNErrorHandling.bindAjaxError=function(){if(window.jQuery&&!window.ZNErrorHandling.ajaxErrorBound){window.ZNErrorHandling.ajaxErrorBound=true;window.jQuery(document).on('ajaxError.znErrorHandling',function(event,xhr){window.ZNErrorHandling.showAjaxError(xhr)})}};if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',window.ZNErrorHandling.bindAjaxError)}else{window.ZNErrorHandling.bindAjaxError()}
</script>
JS;
    }

    /**
     * Get error message 
     * 
     * @param string $langFile
     * @param string $errorMsg = NULL
     * @param mixed  $ex       = NULL
     * 
     * @return string
     */
    public static function message(string $langFile, ?string $errorMsg = NULL, $ex = NULL) : string
    {
        $style  = 'border:solid 1px #E1E4E5;';
        $style .= 'background:#FEFEFE;';
        $style .= 'padding:10px;';
        $style .= 'margin-bottom:10px;';
        $style .= 'font-family:Calibri, Ebrima, Century Gothic, Consolas, Courier New, Courier, monospace, Tahoma, Arial;';
        $style .= 'color:#666;';
        $style .= 'text-align:left;';
        $style .= 'font-size:14px;';

        $exStyle = 'color:#900;';

        if( ! is_array($ex) )
        {
            $ex = '<span style="'.$exStyle .'">'.$ex.'</span>';
        }
        else
        {
            $newArray = [];

            if( ! empty($ex) ) foreach( $ex as $k => $v )
            {
                $newArray[$k] = $v;
            }

            $ex = $newArray;
        }

        $str  = "<div style=\"$style\">";

        if( $errorMsg !== NULL )
        {
            $str .= Lang::default('ZN\CoreDefaultLanguage')::select($langFile, $errorMsg, $ex);
        }
        else
        {
            $str .= $langFile;
        }

        $str .= '</div><br>';

        return $str;
    }

    /**
     * Get last error
     * 
     * @param string $type = NULL
     * 
     * @return mixed
     */
    public static function last(?string $type = NULL)
    {
        $result = error_get_last();

        if( $type === NULL )
        {
            return $result;
        }
        else
        {
            return $result[$type] ?? false;
        }
    }

    /**
     * Error log
     * 
     * @param string $message
     * @param int    $type        = 0
     * @param string $destination = NULL
     * @param string $header      = NULL
     * 
     * @return bool
     */
    public static function log(string $message, int $type = 0, ?string $destination = NULL, ?string $header = NULL) : bool
    {
        return error_log($message, $type, $destination, $header);
    }

    /**
     * Get error report
     * 
     * @param int $level = NULL
     * 
     * @return int
     */
    public static function report(int $level = NULL) : int
    {
        if( ! empty($level) )
        {
            return error_reporting($level);
        }

        return error_reporting();
    }

    /**
     * Exception handler
     * 
     * @param void
     * 
     * @return void
     */
    public static function handler(int $errorTypes = E_ALL | E_STRICT)
    {
        set_error_handler([new Exceptions, 'table'], $errorTypes);
    }

    /**
     * Trigger error
     * 
     * @param string $msg
     * @param int    $errorType = E_USER_NOTICE
     * 
     * @return bool
     */
    public static function trigger(string $msg, int $errorType = E_USER_NOTICE) : bool
    {
        return trigger_error($msg, $errorType);
    }

    /**
     * Restore handler
     * 
     * @param void
     * 
     * @return void
     */
    public static function restore()
    {
        restore_error_handler();
    }
}
