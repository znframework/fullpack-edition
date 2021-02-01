<?php namespace ZN\Services;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\IS;
use ZN\Base;
use ZN\Helper;
use ZN\Request;
use ZN\Support;
use ZN\Services\Exception\InvalidArgumentException;

class CURL implements CURLInterface
{
    /**
     * Multiple
     * 
     * @var bool
     */
    protected $multiple;

    /**
     * Init
     * 
     * @var resource
     */
    protected $init;

    /**
     * Options
     * 
     * @var array
     */
    protected $options = [];

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        Support::func('curl_exec', 'CURL');
    }

    /**
     * Magic Destructor
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Magic Call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return CURL
     */
    public function __call($method, $parameters)
    {
        $option = Helper::toConstant($method, 'CURLOPT_');

        $this->options[$option] = $parameters[0] ?? NULL;

        return $this;
    }

    /**
     * Multiple
     * 
     * @param callback $callback
     * 
     * @return mixed
     */
    public function multiple()
    {
        $this->multiple = true;

        return $this;
    }

    /**
     * Init
     * 
     * @param string $url = NULL
     * 
     * @return CURL
     */
    public function init(String $url = NULL) : CURL
    {
        if( $url !== NULL && ! IS::URL($url) )
        {
            $url = Request::getSiteURL($url);
        }

        if( $this->multiple )
        {
            $this->multipleInitialize($url);
        }
        else
        {
            $this->multipleInformations = NULL;
            
            $this->init = curl_init($url);
        }

        return $this;
    }

    /**
     * Execute
     * 
     * @return mixed|false
     */
    public function exec()
    {
        if( $this->multiple )
        {
            return $this->multipleExecute();
        }
        else
        {
            return $this->singleExecute();
        }   
    }

    /**
     * Escape
     * 
     * @param string $str
     * 
     * @return String
     */
    public function escape(String $str) : String
    {
        if( ! Base::isResourceObject($this->init) )
        {
            throw new InvalidArgumentException(NULL, '$this->init');
        }

        return curl_escape($this->init, $str);
    }

    /**
     * Unescape
     * 
     * @param string $str
     * 
     * @return string
     */
    public function unescape(String $str) : String
    {
        if( ! Base::isResourceObject($this->init) )
        {
            throw new InvalidArgumentException(NULL, '$this->init');
        }

        return curl_unescape($this->init, $str);
    }

    /**
     * Info
     * 
     * @param string $opt = NULL
     * 
     * @return mixed
     */
    public function info(String $opt = NULL)
    {
        if( isset($this->multipleInformations) )
        {
            return $this->multipleInformations();
        }

        return $this->singleInformations($opt);
    }

    /**
     * Error
     * 
     * @return string
     */
    public function error() : String
    {
        if( ! Base::isResourceObject($this->init) )
        {
            throw new InvalidArgumentException(NULL, '$this->init');
        }

        return curl_error($this->init);
    }

    /**
     * Error Number
     * 
     * @return int
     */
    public function errno() : Int
    {
        if( ! Base::isResourceObject($this->init) )
        {
            throw new InvalidArgumentException(NULL, '$this->init');
        }

        return curl_errno($this->init);
    }

    /**
     * Pause
     * 
     * @param string|int $bitmask = 0
     * 
     * @return string
     */
    public function pause($bitmask = 0) : Int
    {
        if( ! empty($this->init) )
        {
            return curl_pause($this->init, Helper::toConstant($bitmask, 'CURLPAUSE_'));
        }

        return false;
    }

    /**
     * Reset
     * 
     * @return bool
     */
    public function reset() : Bool
    {
        if( ! empty($this->init) )
        {
            curl_reset($this->init);

            return true;
        }

        return false;
    }

    /**
     * Option
     * 
     * @param string $options
     * @param mixed  $value
     * 
     * @return CURL
     */
    public function option(String $options, $value) : CURL
    {
        $this->options[Helper::toConstant($options, 'CURLOPT_')] = $value;

        return $this;
    }

    /**
     * Close
     * 
     * @return bool
     */
    public function close() : Bool
    {
        $init = $this->init;

        if( Base::isResourceObject($init) )
        {
            $this->init = NULL;

            curl_close($init);

            return true;
        }

        return false;
    }

    /**
     * Error Value
     * 
     * @param int $errno = 0
     * 
     * @return string
     */
    public function errval(Int $errno = 0) : String
    {
        return curl_strerror($errno);
    }

    /**
     * Version
     * 
     * @param string $data = NULL
     * 
     * @return array|string|false
     */
    public function version($data = NULL)
    {
        $version = curl_version();

        if( $data === NULL )
        {
            return $version;
        }
        else
        {
            return $version[$data] ?? false;
        }
    }

    /**
     * Protected single execute
     */
    protected function singleExecute()
    {
        if( ! Base::isResourceObject($this->init) )
        {
            throw new InvalidArgumentException(NULL, '$this->init');
        }

        curl_setopt_array($this->init, $this->options);

        $this->options = [];

        return curl_exec($this->init);
    }

    /**
     * Protected multiple execute
     */
    protected function multipleExecute()
    {
        $multiInit = curl_multi_init();

        foreach( $this->inits as $key => $init )
        {
            curl_multi_add_handle($multiInit, $this->inits[$key]);
        }

        do 
        {
            curl_multi_exec($multiInit, $running);
            curl_multi_select($multiInit);
        } 
        while( $running > 0 );
        
        $return = [];

        foreach( $this->inits as $key => $init )
        {
            $this->multipleInformations[] = curl_multi_info_read($multiInit);

            $result[] = curl_multi_getcontent($this->inits[$key]);

            curl_multi_remove_handle($multiInit, $this->inits[$key]);
        }
        
        curl_multi_close($multiInit);

        $this->multiple = NULL;
        $this->inits    = [];

        return $result;
    }

    /**
     * Protected multiple initialize
     */
    protected function multipleInitialize($url)
    {
        $this->inits[$url] = curl_init($url);

        if( $this->options )
        {
            curl_setopt_array($this->inits[$url], $this->options);

            $this->options = [];
        }
    }

    /**
     * Protected multiple informations
     */
    protected function multipleInformations()
    {
        $return = $this->multipleInformations;

        return $return;
    }

    /**
     * Protected single informations
     */
    protected function singleInformations($opt)
    {
        if( ! Base::isResourceObject($this->init) )
        {
            throw new InvalidArgumentException(NULL, '$this->init');
        }

        if( $opt === NULL )
        {
            return curl_getinfo($this->init);
        }

        return curl_getinfo($this->init, Helper::toConstant($opt, 'CURLINFO_'));
    }
}
