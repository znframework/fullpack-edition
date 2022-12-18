<?php namespace ZN\Validation;
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
use ZN\Singleton;
use ZN\Request\Method;
use ZN\DataTypes\Arrays;
use ZN\Validation\Exception\InvalidArgumentException;

class Data implements DataInterface
{
    use RulesPropertiesTrait;

    /**
     * Keeps options.
     * 
     * @var array
     */
    protected $options   = ['post', 'get', 'request', 'data'];

    /**
     * Keeps match method
     * 
     * @var array
     */
    protected $matchMethods = ['match', 'matchpassword'];

    /**
     * Keeps errors
     * 
     * @var array
     */
    protected $errors   = [];

    /**
     * Keeps error
     * 
     * @var array
     */
    protected $error    = [];

    /**
     * Keeps messages
     * 
     * @var array
     */
    protected $messages = [];

    /**
     * Keeps user messages
     * 
     * @var array
     */
    protected $userMessages = [];

    /**
     * Keeps index
     * 
     * @var int
     */
    protected $index = 0;

    /**
     * @var string
     */
    protected $method;

    /**
     * Defines rules for control of the grant.
     * 
     * @param string $name
     * @param array  $config   = []
     * @param string $viewName = ''
     * @param string $met      = 'post' - options[post|get]
     * 
     * @return void
     */
    public function rules(string $name, array $config = [], $viewName = '', string $met = 'post')
    {
        if( ! in_array($met, $this->options) )
        {
            throw new InvalidArgumentException(NULL, '4. ');
        }    

        if( is_array($this->setMethodType($name, $met)) )
        {
            return $this->setMultipleRules($name, $config, $viewName, $met);
        }      

        $met      = $this->settings['method'] ?? 'post';
        $viewName = $this->settings['value']  ?? $viewName;

        $config = array_merge
        (
            $config,
            $this->settings['config']   ?? [],
            $this->settings['validate'] ?? [],
            $this->settings['secure']   ?? []
        );

        $this->settings = [];

        $viewName = $viewName ?: $name;
        $edit     = $this->setMethodType($name, $met);

        if( ! isset($edit) ) return false;

        $this->method = $met;

        foreach( $config as $key => $val )
        {
            $function = is_numeric($key) ? $val : $key;

            if( $this->isValidatorObject($function) )
            {
                if( is_numeric($key) )
                {  
                    $this->validInArray($function, $edit, $name, [':name' => $viewName]);
                }
                else
                {
                    $this->validIssetArray($function, $edit, (array) $val, $name, $viewName);
                }
            }    
        }

        $this->setMethodNewValue($name, $edit, $met);

        array_push($this->errors, $this->messages);

        $this->defaultVariables();
    }

    /**
     * Sets user messages
     * 
     * @param array $settings
     */
    public function messages(array $settings)
    {
        $this->userMessages = $settings;
    }

    /**
     * Add errors
     * 
     * @param string $error
     * @param string $name = NULL
     * 
     * @return self
     */
    public function addError(string $error, string $name = NULL)
    {
        if( $name )
        {
            $this->error[$name] = $error;
        }
        
        $this->errors[][$this->index] = $error . '<br>'; $this->index++;
    }

    /**
     * It checks the data.
     * 
     * @param string $submit = NULL
     * @param string &$error = NULL
     * 
     * @return bool
     */
    public function check(string $submit = 'all', &$error = NULL) : bool
    {
        $session = Singleton::class('ZN\Storage\Session');
        
        $getValidationFormName = Method::request('ValidationFormName');

        $rules  = $session->select('FormValidationRules'  . $getValidationFormName);
        $method = $session->select('FormValidationMethod' . $getValidationFormName) ?: 'post';

        if( $submit !== NULL && ! $method::$submit() ) 
        {
            return false;
        }

        if( empty($rules) ) 
        {
            $this->errors[] = ['Rule check error!']; return false;
        }
        
        if( is_array($rules) )
        {
            foreach( $rules as $name => $rule )
            {
                if( isset($method::all()[$name]) )
                {
                    $value = $rule['value'] ?? $name;
                
                    unset($rule['value']);
    
                    $rule = Arrays\Unidimensional::do($rule);
             
                    $this->rules($name, $rule, $value, $method);
                } 
            } 
        }

        return ! (Bool) ($error = $this->error('string'));
    }

    /**
     * Get error
     * 
     * @param string $name = 'array' - options[array|string]
     * @param string $separator = '<br>'
     */
    public function error(string $name = 'array', string $separator = '<br>')
    {
        $defaultSeparator = '<br>';
        
        if( $name === 'string' || $name === 'array' || $name === 'echo' )
        {
            if( count($this->errors) > 0 )
            {
                $result = '';
                $resultArray = [];

                foreach( $this->errors as $key => $value )
                {
                    if( is_array($value) ) foreach($value as $k => $val)
                    {
                        $result .= $val;
                        $resultArray[] = str_replace($defaultSeparator, '', $val ?? '');
                    }
                }

                if( $name === 'string' || $name === 'echo' )
                {
                    return str_replace($defaultSeparator, $separator, $result);
                }

                if( $name === 'array')
                {
                    return $resultArray;
                }
            }
        }
        else if( isset($this->error[$name]) )        
        {
            return $this->error[$name];
        }

        return false;
    }

    /**
     * Get input post back.
     * 
     * @param string $name
     * @param string $met = 'post' - options[post|get]
     */
    public function postBack(string $name, string $met = 'post')
    {
        $method = $this->setMethodType($name, $met);

        return $method;
    }

    /**
     * Get error inputs.
     * 
     * @return array
     */
    public function errorInputs()
    {
        return array_keys($this->error);
    }

    /**
     * Protected is validator object
     */
    protected function isValidatorObject($object)
    {
        return method_exists('ZN\Validation\Validator', $object);
    }

    /**
     * protected single in array
     * 
     * @param mixed $key
     * 
     * @return void
     */
    protected function validInArray($key, &$data, $name, $viewName)
    {
        if( is_string($stringCleanData = Validator::$key($data)) )
        {
            $data = $stringCleanData;
        }
        elseif( $stringCleanData === false )
        {
            $this->setMessages($key, $name, $viewName);
        }
    }

    /**
     * protected single isset array
     * 
     * @param mixed $key
     * 
     * @return void
     */
    protected function validIssetArray($key, $data, $check, $name, $viewName)
    {
        $data = $this->setMethodType($name, $this->method);
     
        $this->isMatchMethods($key, $check);

        if( ! Validator::$key($data, ...$check) )
        {
            $this->setMessages($key, $name, $this->replaceParameters($check, $viewName));
        }
    }

    /**
     * Protected is match methods
     */
    protected function isMatchMethods($key, &$check)
    {
        if( in_array(strtolower($key ?? ''), $this->matchMethods) && isset($check[0]) )
        {
            $check[0] = Method::{$this->method}($check[0]);
        }
    }

    /**
     * protected messages
     */
    protected function setMessages($type, $name, $check)
    {
        if( $userMessage = ($this->userMessages[$type] ?? NULL) )
        {
            $message = $this->replaceUserMessage($check, $userMessage);
        }
        else
        {
            $message = Lang::default('ZN\Validation\ValidationDefaultLanguage')
                           ::select('ViewObjects', 'validation:'.$type, $check);
        }

        $this->messages[$this->index] = $message.'<br>'; $this->index++;
        $this->error[$name]           = $message;
    }

    /**
     * Protected replace user message
     */
    protected function replaceUserMessage($check, $userMessage)
    {
        return str_replace(array_keys($check), array_values($check), $userMessage ?? '');
    }

    /**
     * Protected replace parameters
     */
    protected function replaceParameters($check, $viewName)
    {
        $newCheck = [];

        foreach( $check as $key => $p )
        {
            $newCheck[] = ':p' . ($key + 1);
        }

        array_unshift($newCheck, ':name');
        array_unshift($check, $viewName);

        return array_combine($newCheck, $check);
    }

    /**
     * Default variables
     * 
     * @param void
     * 
     * @return void
     */
    protected function defaultVariables()
    {
        $this->messages = [];
        $this->index    = 0;
        $this->method   = NULL;
    }

    /**
     * protected method type
     * 
     * @param string $name 
     * @param string $met
     * 
     * @return mixed
     */
    protected function setMethodType($name, $met)
    {
        if( $met === 'data' )
        {
            return $name;
        }

        return Method::$met($name);
    }

    /**
     * protected method new value
     * 
     * @param string $name 
     * @param string $val
     * @param string $met
     * 
     * @return mixed
     */
    protected function setMethodNewValue($name, $val, $met)
    {
        if( $met === 'data' )
        {
            return;
        }

        return Method::$met($name, $val);
    }

    /**
     * protected multiple rules
     * 
     * @param string $name 
     * @param array  $config   = []
     * @param string $viewName = ''
     * @param string $set      = 'post' - options[post|get]
     * 
     * @return void
     */
    protected function setMultipleRules(string $name, array $config = [], $viewName = '', string $met = 'post')
    {
        $postNames = [];
        $postKey   = '';
        $postDatas = (array) Method::$met($name);

        foreach( $postDatas as $key => $postData )
        {
            $postName = $name . $key;

            Method::$met($postName, $postData);

            $postKey = is_array($viewName)
                     ? $viewName[$key] ?? $postName
                     : $postName;

            $this->rules($postName, $config, $postKey, $met);
        }
    }
}
