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

class Response
{   
    /**
     * Prefix 
     * 
     * @var string
     */
    protected static $fix = 'redirect:';

    /**
     * Redirect Invalid Request
     */
    public static function redirectInvalidRequest()
    {
        $invalidRequest = Config::get('Routing', 'requestMethods');

        if( empty($invalidRequest['page']) )
        {
            Helper::report('Error', $getInvalidRequestLang = Lang::default('ZN\CoreDefaultLanguage')::select('Error', 'invalidRequest'), 'InvalidRequestError');
            
            Base::trace($getInvalidRequestLang);
        }
        else
        {
            self::redirect($invalidRequest['page']);
        }
    }

    /**
     * Location
     *
     * @param string $url  = NULL
     * @param int    $time = 0
     * @param array  $data = NULL
     * @param bool   $exit = true
     */
    public static function redirect(string $url = NULL, int $time = 0, array $data = NULL, bool $exit = true, $type = 'location')
    {
        $url = $url ?? '';

        if( ! IS::url($url) )
        {
            $url = Request::getSiteURL($url);
        }

        if( ! empty($data) )
        {
            foreach( $data as $k => $v )
            {
                $_SESSION[self::$fix . $k] = $v;
            }
        }
        
        if( $type === 'location' )
        {
            if( $time > 0 )
            {
                sleep($time);
            }
    
            header('Location: ' . $url, true);   
        }
        else
        {
            header('Refresh:'.$time.'; url='.$url);
        }

        if( $exit === true && ! defined('ZN_REDIRECT_NOEXIT') )
        {
            exit; // @codeCoverageIgnore
        }
    }
}