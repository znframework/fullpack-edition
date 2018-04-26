<?php namespace ZN;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Structure
{
    /**
     * Get structure data
     * 
     * @param string $requestUri = NULL
     * 
     * @return array
     */
    public static function data($requestUri = NULL)
    {
        $namespace    = PROJECT_CONTROLLER_NAMESPACE;
        $openFunction = $function = Config::get('Routing', 'openFunction') ?: 'main';
        $parameters   = [];
        $isFile       = '';
        $page         = '';
        $url          = explode('?', $requestUri ?? In::requestURI());
        $segments     = explode('/', $url[0]);

        # The controller information in the URL to be executed is captured.
        if( isset($segments[0]) )
        {
            $page   = $segments[0];
            $isFile = CONTROLLERS_DIR . ($suffixExtension = Base::suffix($page, '.php'));

            # Virtual Controller - Added[5.6.0]
            if( ! is_file($isFile) )
            {
                $isFile = EXTERNAL_CONTROLLERS_DIR . $suffixExtension;
            }

            unset($segments[0]);
        }

        # The method information in the URL to be executed is captured.
        if( isset($segments[1]) )
        {
            $function = $segments[1];

            unset($segments[1]);
        }

        # The segments information in the URL to be executed is captured.
        if( isset($segments[2]) )
        {
            $parameters = $segments;
        }

        return
        [
            'page'         => $page,
            'file'         => $isFile,
            'function'     => $function,
            'namespace'    => $namespace,
            'openFunction' => $openFunction,
            'subdir'       => $ifTrim ?? NULL,
            'parameters'   => array_values($parameters)
        ];
    }

    /**
     * Structure short path descriptions.
     * 
     * @param void
     * 
     * @return void
     */
    public static function defines()
    {
        define('STRUCTURE_DATA'     , self::data());
        define('CURRENT_COPEN_PAGE' , STRUCTURE_DATA['openFunction']);
        define('CURRENT_CPARAMETERS', STRUCTURE_DATA['parameters']);
        define('CURRENT_CFILE'      , STRUCTURE_DATA['file']);
        define('CURRENT_CFUNCTION'  , STRUCTURE_DATA['function']);
        define('CURRENT_CPAGE'      , ($page = STRUCTURE_DATA['page']) . '.php');
        define('CURRENT_CONTROLLER' , $page);
        define('CURRENT_CNAMESPACE' , $namespace = STRUCTURE_DATA['namespace'] );
        define('CURRENT_CCLASS'     , $namespace . CURRENT_CONTROLLER);
        define('CURRENT_CFPATH'     , str_replace
        (
            CONTROLLERS_DIR, '', CURRENT_CONTROLLER) . '/' . CURRENT_CFUNCTION
        );
        define('CURRENT_CFURI'      , strtolower(CURRENT_CFPATH));
        define('CURRENT_CFURL'      , Request::getSiteURL() . CURRENT_CFPATH);
    }
}
