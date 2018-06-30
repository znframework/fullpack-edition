<?php namespace ZN\Storage;
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
use ZN\Config;
use ZN\Datatype;
use ZN\DataTypes\Arrays;
use ZN\DataTypes\Strings;
use ZN\Cryptography\Encode;

trait SessionCookieCommonTrait
{
    /**
     * Regenarate session
     * 
     * @var bool
     */
    protected $regenerate = true;

    /**
     * Encode session keys
     * 
     * @var array
     */
    protected $encode = [];

    /**
     * Magic call
     * 
     * @param string $method
     * @param array  $parameters
     * 
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $split = Strings\Split::upperCase($method);

        if( Arrays\GetElement::last($split) === 'Delete' )
        {
            $method = 'delete';

            return $this->delete($split[0]);
        }

        if( $method === 'all' )
        {
            $method = 'selectAll';

            return $this->$method();
        }

        if( $param = ($parameters[0] ?? NULL) )
        {
            return $this->insert($method, $param);
        }

        return $this->select($method);
    }

    /**
     * Magic constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct(Array $config = [])
    {
        $this->config = $config ?: Config::default(__CLASS__ . 'DefaultConfiguration')
                                         ::get('Storage', strtolower(Datatype::divide(__CLASS__, '/', -1)));

        $this->start();
    }

    /**
     * Session start
     * 
     * @param void
     * 
     * @return void
     */
    public static function start()
    {
        if( ! isset($_SESSION) )
        {
            session_start();
        }
    }

    /**
     * Encode session key & value
     * 
     * @param string $nameAlgo  = NULL
     * @param string $valueAlgo = NULL
     * 
     * @return $this
     */
    public function encode(String $nameAlgo = NULL, String $valueAlgo = NULL)
    {
        $this->encode['name']  = $nameAlgo;
        $this->encode['value'] = $valueAlgo;

        return $this;
    }

    /**
     * Decode only session key
     * 
     * @param string $nameAlgo
     * 
     * @return $this
     */
    public function decode(String $nameAlgo)
    {
        $this->encode['name'] = $nameAlgo;

        return $this;
    }

    /**
     * Regenerate status
     * 
     * @param bool $regenerate = true
     * 
     * @return $this
     */
    public function regenerate(Bool $regenerate = true)
    {
        $this->regenerate = $regenerate;

        return $this;
    }

    /**
     * Protected regenerate session id
     */
    protected function regenerateSessionId()
    {
        if( $this->regenerate === true )
        {
            session_regenerate_id();
        }
    }

    /**
     * Protected encode name value
     */
    protected function encodeNameValue(&$name, &$value = NULL)
    {
        if( ! empty($this->encode) )
        {
            $this->encodeDataByType('name' , $name );

            if( $value !== NULL )
            {
                $this->encodeDataByType('value', $value);
            }       
        }

        if( ! isset($this->encode['name']) )
        {
            $this->defaultEncodeData($name);
        }

        $this->encode = [];
    }

    /**
     * Protected default encode data
     */
    protected function defaultEncodeData(&$name)
    {
        if( ($encode = $this->config['encode']) === true )
        {
            $name = md5($name);
        }
        elseif( is_string($encode) )
        {
            $this->encodeDataIfValidHash($name, $encode);
        }
    }

    /**
     * Protected encode data by type
     */
    protected function encodeDataByType($type = 'value', &$data)
    {
        if( isset($this->encode[$type]) )
        {
            $this->encodeDataIfValidHash($data, $this->encode[$type]);
        }
    }

    /**
     * Protected encode data if valid hash
     */
    protected function encodeDataIfValidHash(&$data, $encode)
    {
        if( IS::hash($encode) )
        {
            $data = Encode\Type::create($data, $encode);
        }
    }

    /**
     * protected default variable
     * 
     * @param void
     * 
     * @return void
     */
    protected function defaultVariable()
    {
        $this->name       = NULL;
        $this->value      = NULL;
        $this->encode     = [];
        $this->regenerate = true;
    }
}
