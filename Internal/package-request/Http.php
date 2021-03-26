<?php namespace ZN\Request;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Request;
use ZN\Datatype;
use ZN\Ability\Singleton;
use ZN\Request\Exception\InvalidArgumentException;

class Http implements HttpInterface
{
    use Singleton;

    /**
     * Settings
     * 
     * @var array
     */
    protected static $settings;

    /**
     * Types
     * 
     * @var array
     */
    protected static $types =
    [
        'post',
        'get',
        'env',
        'server',
        'request'
    ];

    /**
     * Http Messages
     * 
     * @var array
     */
    protected static $messages =
    [
        # 1XX Information
        '100|continue'              => '100 Continue',
        '101|switchProtocols'       => '101 Switching Protocols',
        '103|checkpoint'            => '103 Checkpoint',

        # 2XX Successful
        '200|ok'                    => '200 OK',
        '201|created'               => '201 Created',
        '202|accepted'              => '202 Accepted',
        '203|nonAuthInfo'           => '203 Non-Authoritative Information',
        '204|noContent'             => '204 No Content',
        '205|resetContent'          => '205 Reset Content',
        '206|partialContent'        => '206 Partial Content',

        # 3XX Redirection
        '300|multipleChoices'       => '300 Multiple Choices',
        '301|movedPermanent'        => '301 Moved Permanently',
        '302|found'                 => '301 Found',
        '303|seeOther'              => '303 See Other',
        '304|notModified'           => '304 Not Modified',
        '306|switchProxy'           => '306 Switch Proxy',
        '307|temporaryRedirect'     => '307 Temporary Redirect',
        '308|resumeIncomplete'      => '308 Resume Incomplete',

        # 4XX Client Error
        '400|badRequest'            => '400 Bad Request',
        '401|unauth'                => '401 Unauthorized',
        '402|paymentRequired'       => '402 Payment Required',
        '403|forbidden'             => '403 Forbidden',
        '404|notFound'              => '404 Not Found',
        '405|methodNotAllowed'      => '405 Method Not Allowed',
        '406|notAccept'             => '406 Not Acceptable',
        '407|proxyAuth'             => '407 Proxy Authentication Required',
        '408|requestTimeout'        => '408 Request Timeout',
        '409|conflict'              => '409 Conflict',
        '410|gone'                  => '410 Gone',
        '411|lengthRequired'        => '411 Length Required',
        '412|preconditionFailed'    => '412 Precondition Failed',
        '413|requestEntity'         => '413 Request Entity Too Large',
        '414|requestUri'            => '414 Request-URI Too Long',
        '415|unsupportedMedia'      => '415 Unsupported Media Type',
        '416|requestedRange'        => '416 Requested Range Not Satisfiable',
        '417|expectFailed'          => '417 Expectation Failed',

        # 5XX Server Error
        '500|internalServerError'   => '500 Internal Server Error',
        '501|notImplement'          => '501 Not Implemented',
        '502|badGateway'            => '502 Bad Gateway',
        '503|serviceUnavailable'    => '503 Service Unavailable',
        '504|gatewayTimeout'        => '504 Gateway Timeout',
        '505|versionNotSupported'   => '505 HTTP Version Not Supported',
        '511|authRequired'          => '511 Network Authentication Required'
    ];
    
    /**
     * Response
     * 
     * @param int    $code
     * @param string $version = '1.1'
     */
    public static function response(int $code, string $version = '1.1')
    {
        header("HTTP/$version " . self::code($code));
    }

    /**
     * Fix
     * 
     * @param bool $security = false
     * 
     * @return string
     */
    public static function fix(bool $security = false) : string
    {
        return ( $security === false )
               ? 'http://'
               : 'https://';
    }

    /**
     * Host
     * 
     * @return string
     */
    public static function host() : string
    {
        return Server::data('host');
    }

    /**
     * User Agent
     * 
     * @return string
     */
    public static function userAgent() : string
    {
        return Server::data('userAgent');
    }

    /**
     * Accept
     * 
     * @return string
     */
    public static function accept() : string
    {
        return Server::data('accept');
    }

    /**
     * Language
     * 
     * @return string
     */
    public static function language() : string
    {
        return Server::data('acceptLanguage');
    }

    /**
     * Encoding
     * 
     * @return string
     */
    public static function encoding() : string
    {
        return Server::data('acceptEncoding');
    }

    /**
     * Cookie
     * 
     * @return string
     */
    public static function cookie() : string
    {
        return Server::data('cookie');
    }

    /**
     * Connection
     * 
     * @return string
     */
    public static function connection() : string
    {
        return Server::data('connection');
    }

    /**
     * Request method type
     * 
     * @param string ...$methods
     * 
     * @returm bool
     */
    public static function isRequestMethod(...$methods) : bool
    {
        return Request::isMethod(...$methods);
    }

    /**
     * Request is ajax
     * 
     * @param bool
     */
    public static function isAjax() : bool
    {
        return Request::isAjax();
    }

    /**
     * Get Browser Lang
     * 
     * @param string $default = 'en'
     * 
     * @return string
     */
    public static function browserLang(string $default = 'en') : string
    {
        return strtolower(substr(($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? $default), 0, 2));
    }

    /**
     * Code
     * 
     * @param int|string $code = 200
     * 
     * @return string
     */
    public static function code($code = 200) : string
    {
        $messages = Datatype::multikey(self::$messages);

        if( isset($messages[$code]) )
        {
            return $messages[$code];
        }

        return false;
    }

    /**
     * Message
     * 
     * @param string $message
     * 
     * @return string
     */
    public static function message(string $message) : string
    {
        return self::code($message);
    }

    /**
     * Name
     * 
     * @param string $name
     * 
     * @return Http
     */
    public static function name(string $name) : Http
    {
        self::$settings['name'] = $name;

        return self::singleton();
    }

    /**
     * Value
     * 
     * @param mixed $value
     * 
     * @return Http
     */
    public static function value($value) : Http
    {
        self::$settings['value'] = $value;

        return self::singleton();
    }

    /**
     * Input
     * 
     * @param string $input
     * 
     * @return Http
     */
    public static function input(string $input) : Http
    {
        if( in_array($input, self::$types) )
        {
            self::$settings['input'] = $input;
        }
        else
        {
            throw new InvalidArgumentException('Error', 'invalidInput', $input.' : get, post, server, env, request');
        }

        return self::singleton();
    }

    /**
     * Select
     * 
     * @param string $name
     * 
     * @return mixed
     */
    public static function select(string $name = NULL)
    {
        $name  = self::$settings['name']  ?? $name;
        $input = self::$settings['input'] ?? false;

        self::$settings = [];

        return Method::$input($name);
    }

    /**
     * Insert
     * 
     * @param string $name  = NULL
     * @param mixed  $value = NULL
     * 
     * @return bool
     */
    public static function insert(string $name = NULL, $value = NULL) : bool
    {
        $name  = self::$settings['name']  ?? $name;
        $input = self::$settings['input'] ?? false;
        $value = self::$settings['value'] ?? $value;

        self::$settings = [];

        return Method::$input($name, $value);
    }

    /**
     * Insert
     * 
     * @param string $name  = NULL
     * 
     * @return bool
     */
    public static function delete(string $name = NULL) : bool
    {
        $name  = self::$settings['name']  ?? $name;
        $input = self::$settings['input'] ?? false;

        self::$settings = [];

        switch( $input )
        {
            case 'post'    : unset($_POST[$name]);    break;
            case 'get'     : unset($_GET[$name]);     break;
            case 'env'     : unset($_ENV[$name]);     break;
            case 'server'  : unset($_SERVER[$name]);  break;
            case 'request' : unset($_REQUEST[$name]); break;
        }

        return true;
    }
}