<?php namespace ZN\Hypertext;
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
use ZN\Lang;
use ZN\Request;
use ZN\Singleton;
use ZN\Protection\Json;

trait FormElementsTrait
{
    /**
     * Keeps Enctypes
     * 
     * @var array
     */
    protected $enctypes =
    [
        'multipart'     => 'multipart/form-data',
        'application'   => 'application/x-www-form-urlencoded',
        'text'          => 'text/plain'
    ];

    /**
     * Keeps Postback Data
     * 
     * @var array
     */
    protected $postback = [];

    /**
     * Keeps Validate Rules
     * 
     * @var array
     */
    protected $validate = [];

    /**
     * Keeps validation method messages
     * 
     * @var string
     */
    protected $vMethodMessages = NULL;

    /**
     * Email control
     * 
     * @return string
     */
    public function vEmail()
    {
        return $this->onInvalidEventPattern('^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$', 'email');
    }

    /**
     * URL control
     * 
     * @return string
     */
    public function vUrl()
    {
        return $this->onInvalidEventPattern('^(\w+:)?//.*', 'url');
    }

    /**
     * Numeric control
     * 
     * @return string
     */
    public function vNumeric()
    {
        return $this->onInvalidEventPattern('^[0-9]+$', 'numeric');
    }

    /**
     * Alpha control
     * 
     * @return string
     */
    public function vAlpha()
    {
        return $this->onInvalidEventPattern('^[a-zA-Z]+$', 'alpha');
    }

    /**
     * Alnum control
     * 
     * @return string
     */
    public function vAlnum()
    {
        return $this->onInvalidEventPattern('^([a-zA-Z]|[0-9])+$', 'alnum');
    }

    /**
     * Required control
     * 
     * @return string
     */
    public function vRequired()
    {
        return $this->onInvalidEventPattern('^.+$', 'required');
    }

    /**
     * Message control
     * 
     * @return string
     * 
     * @codeCoverageIgnore
     */
    public function vMessage(String $message)
    {
        $this->vMethodMessages = $message;
        
        return $this;
    }

    /**
     * Between control
     * 
     * @param int $min = 0
     * @param int $max = 0
     * 
     * @return string
     */
    public function vBetween(Int $min = 0, Int $max = 0)
    {
        return $this->setJavascriptValidation
        (
            'ZNValidationBetween', 
            ['betweenBoth' => [':p1' => $min, ':p2' => $max]],
            ['betweenBoth' => [$min, $max]],
            [$min, $max]  
        );
    }

    /**
     * Captcha control
     * 
     * @return string
     */
    public function vCaptcha()
    {
        return $this->setJavascriptValidation('ZNValidationCaptcha', 'captcha');
    }

    /**
     * Captcha control
     * 
     * @return string
     */
    public function vAnswer()
    {
        return $this->setJavascriptValidation('ZNValidationAnswer', 'answer');
    }

    /**
     * Match control
     * 
     * @return string
     */
    public function vMatch(String $selector)
    {
        return $this->setJavascriptValidation('ZNValidationMatch', 'match', ['match' => $selector], [Base::presuffix($selector, '\'')]);
    }

    /**
     * Match password control
     * 
     * @return string
     */
    public function vMatchPassword(String $selector)
    {
        return $this->setJavascriptValidation('ZNValidationMatch', 'matchPassword', ['matchPassword' => $selector], [Base::presuffix($selector, '\'')]);
    }

    /**
     * Phone control
     * 
     * @return string
     */
    public function vPhone(String $pattern = NULL)
    {
        return $this->setJavascriptValidation('ZNValidationPhone', 'phone', ['phone' => $pattern], [Base::presuffix($pattern, '\'')]);
    }

    /**
     * Pattern control
     * 
     * @param string $pattern
     * 
     * @return string
     */
    public function vPattern(String $pattern)
    {
        return $this->onInvalidEventPatternWithoutValidate($pattern, 'pattern');
    }

    /**
     * Identity control
     * 
     * @return string
     */
    public function vIdentity()
    {
        return $this->setJavascriptValidation('ZNValidationIdentity', 'identity');
    }

    /**
     * Numeric control
     * 
     * @param int $min = 0
     * @param int $max = NULL
     * 
     * @return string
     */
    public function vLimit(Int $min = 0, Int $max = NULL)
    {
        $key['minchar'] = [':p1' => $min];
        $typ['minchar'] = $min;

        if( $max !== NULL )
        {
            $key['maxchar'] = [':p1' => $max];
            $typ['maxchar'] = $max;
        }

        return $this->onInvalidEventPattern('.{' . $min . ',' . $max . '}', $key, [], $typ);
    }

    /**
     * Minchar control
     * 
     * @param int $min = 0
     * @param int $max = NULL
     * 
     * @return string
     */
    public function vMinchar(Int $min = 0)
    {
        return $this->minlength($min)->onInvalidEventAttributeValidate('minchar', [':p1' => $min], ['minchar' => $min]);
    }

    /**
     * Maxchar control
     * 
     * @param int $max = 0
     * 
     * @return string
     */
    public function vMaxchar(Int $max = 0)
    {
        return $this->maxlength($max)->onInvalidEventAttributeValidate('maxchar', [':p1' => $max], ['maxchar' => $max]);
    }

    /**
     * Defines validate rules.
     * 
     * @param mixed ...$validate
     * 
     * @return self
     */
    public function validate(...$validate)
    {
        if( $this->validate === [] )
        {
            $this->validate = $validate;
        }
        else
        {
            $this->validate = array_merge($this->validate, $validate);
        }

        return $this;
    }

    /**
     * Sets postback
     * 
     * @param bool   $postback = true
     * @param string $type     = 'post' - options[post|get]
     * 
     * @return self
     */
    public function postBack(Bool $postback = true, String $type = 'post')
    {
        $this->postback['bool'] = $postback;
        $this->postback['type'] = $type;

        return $this;
    }

    /**
     * Controls CSRF
     * 
     * @return self
     */
    public function csrf()
    {
        $this->settings['token'] = true;

        return $this;
    }

    /**
     * Exluding data
     * 
     * @param mixed $exclude
     * 
     * @return self
     */
    public function excluding($exclude)
    {
        $this->settings['exclude'] = (array) $exclude;

        return $this;
    }

    /**
     * Including data
     * 
     * @param mixed $include
     * 
     * @return self
     */
    public function including($include)
    {
        $this->settings['include'] = (array) $include;

        return $this;
    }

    /**
     * Sets process type.
     * 
     * @param string $type - [insert|update]
     * 
     * @return self
     */
    public function process(String $type)
    {
        $this->settings['process'] = $type;

        return $this;
    }

    /**
     * Duplicate check with insert process
     * 
     * @return self
     */
    public function duplicateCheck()
    {
        $this->settings['duplicateCheck'] = true;

        return $this;
    }

    /**
     * Database Where Clause
     * 
     * @param mixed  $column
     * @param string $value   = NULL
     * @param string $logical = 'and'
     * 
     * @return self
     */
    public function where($column, String $value = NULL, String $logical = 'and')
    {
        $this->settings['where']       = true;
        $this->settings['whereValue']  = $value;
        $this->settings['whereColumn'] = $column;

        Singleton::class('ZN\Database\DB')->where($column, $value, $logical);

        return $this;
    }

    /**
     * Defines SQL Query
     * 
     * @param string $query
     * 
     * @return self
     */
    public function query(String $query)
    {
        $this->settings['query'] = $query;

        return $this;
    }

    /**
     * Sets table
     * 
     * @param string $table
     * 
     * @return self
     */
    public function table(String $table)
    {
        $this->settings['table'] = $table;

        return $this;
    }

    /**
     * Order 
     * 
     * @param string $type  = 'desc'
     * @param string $flags = 'regular'
     * 
     * @return self
     */
    public function order(String $type = 'desc', String $flags = 'regular')
    {
        $this->settings['order']['type']  = $type;
        $this->settings['order']['flags'] = $flags;

        return $this;
    }

    /**
     * Sets attributes
     * 
     * @param array $attr = []
     * 
     * @return self
     */
    public function attr(Array $attr = [])
    {
        $settings = [];

        if( isset($this->settings['attr']) && is_array($this->settings['attr']) )
        {
            $settings = $this->settings['attr'];
        }

        $this->settings['attr'] = array_merge($settings, $attr);

        return $this;
    }

    /**
     * Sets Form Action
     * 
     * @param string $url = NULL
     * 
     * @return self
     */
    public function action(String $url = NULL)
    {
        $this->settings['attr']['action'] = IS::url($url) ? $url : Request::getSiteURL($url);

        return $this;
    }

    /**
     * Sets Form Enctype
     * 
     * @param string $enctype
     * 
     * @return self
     */
    public function enctype(String $enctype)
    {
        if( isset($this->enctypes[$enctype]) )
        {
            $enctype = $this->enctypes[$enctype];
        }

        $this->_element(__FUNCTION__, $enctype);

        return $this;
    }

    /**
     * Sets select options
     * 
     * @param mixed  $key
     * @param string $value = NULL
     * 
     * @return self
     */
    public function option($key, String $value = NULL)
    {
        if( is_array($key) )
        {
            $this->settings['option'] = $key;
        }
        else
        {
            $this->settings['option'][$key] = $value;
        }

        return $this;
    }

    /**
     * Protected set javascript validation
     */
    protected function setJavascriptValidation($name, $lang, $rule = NULL, $param = [])
    {
        $this->getJavascriptValidationFunction[$name] = $function = $name . md5($name);

        $this->validate[] = $rule ?: $lang;

        return $this->onkeyup($function . '(this, ' . Base::suffix(implode(', ', $param), ', ') . '\''.$this->setCustomValidity($lang).'\')') 
                    ->required();
    }

    /**
     * Protected on invalid event pattern
     */
    protected function onInvalidEventPattern($pattern, $key, $check = [], $type = NULL)
    {
        $this->validate[] = $type ?: $key;

        return $this->onInvalidEventPatternWithoutValidate($pattern, $key, $check);
    }

    /**
     * Protected on invalid event pattern without validate
     */
    protected function onInvalidEventPatternWithoutValidate($pattern, $key, $check = [])
    {
        return $this->required()->pattern($pattern)->onInvalidEventCustomValidity($key, $check);
    }

    /** 
     * Protected on invalid event attribute validate
     */
    protected function onInvalidEventAttributeValidate($key, $check = [], $rule = NULL)
    {
        $this->validate[] = $rule ?: $key;

        return $this->required()->onInvalidEventCustomValidity($key, $check);
    }

    /**
     * Protected on invalid event custom validity
     */
    protected function onInvalidEventCustomValidity($key, $check = [])
    {
        $this->vMethodMessages .= $this->setCustomValidity($key, $check) . ' ';

        return $this;
    }

    /**
     * Protected get validate method messages
     */
    protected function getVMethodMessages()
    {
        if( $this->vMethodMessages !== NULL )
        {
            $this->oninvalid('setCustomValidity(\'' . rtrim($this->vMethodMessages) . '\')')->oninput('setCustomValidity(\'\')')->validate(...$this->validate);

            $this->vMethodMessages = NULL;
        } 
    }

    /**
     * Protected set custom validity
     */
    protected function setCustomValidity($key, $check = [])
    {
        $message = NULL;
        
        if( is_scalar($key) )
        {
            $message = $this->getValidationLangValue($key, $check);
        }
        else foreach( $key as $k => $c )
        {
            $message .= $this->getValidationLangValue($k, $c) . ' ';
        }

        return rtrim($message);
    }

    /**
     * Protected get validation lang value
     */
    protected function getValidationLangValue($key, $check)
    {
        $check[':name'] = 'Input';

        return Lang::default('ZN\Validation\ValidationDefaultLanguage')::select('ViewObjects', 'validation:'.$key, $check) ?: $key;
    }

    /**
     * Protected Postback
     */
    protected function _postback($name, &$default, $type = NULL)
    {
        if( isset($this->postback['bool']) && $this->postback['bool'] === true )
        {
            $method = ! empty($this->method) ? $this->method : $this->postback['type'];
    
            $this->postback = [];

            if( $type === 'checkbox' || $type === 'radio' )
            {
                // @codeCoverageIgnoreStart
                if( $method::$name() === $default )
                {
                    $this->checked();
                }    
                // @codeCoverageIgnoreEnd
            }
            else
            {
                $default = Singleton::class('ZN\Validation\Data')->postBack($name, $method) ?: $default;
            }   
        }
    }

    /**
     * Protected Validate
     */
    protected function _validate($name, $attrName)
    {
        if( ! empty($this->validate) )
        {
            $this->validateUsageThisForm = true;
            
            $session = Singleton::class('ZN\Storage\Session');

            $validate[$name]           = $this->validate;
            $validate[$name]['value']  = $this->settings['attr']['alias'] ?? $attrName;

            $rules = array_merge($session->select('FormValidationRules' . $this->getValidationFormName) ?: [], $validate);

            $session->insert('FormValidationMethod' . $this->getValidationFormName, $this->method);
            $session->insert('FormValidationRules' . $this->getValidationFormName, $rules);
 
            $this->validate = [];
        }
    } 

    /**
     * Protected Get Row
     */
    protected function _getrow($type, $value, &$attributes)
    {
        if( $row = ($this->settings['getrow'] ?? NULL) )
        {
            $rowval = $row->{$attributes['name']} ?? NULL;

            if( $type === 'textarea' || $type === 'select' )
            {
                return $value ?: $rowval; // @codeCoverageIgnore
            }

            $attributes['value'] = $value ?: $rowval;
            
            // For radio
            if( $type === 'radio' && $value == $rowval )
            {
                $attributes['checked'] = 'checked'; // @codeCoverageIgnore
            }

            // For checkbox
            if( $type === 'checkbox' )
            {
                // @codeCoverageIgnoreStart
                if( Json::check($rowval) )
                {
                    $rowval = json_decode($rowval, true);

                    if( in_array($value, $rowval) )
                    {
                        $attributes['checked'] = 'checked';
                    }
                }
                else
                {
                    if( ! empty($rowval) )
                    {
                        $attributes['checked'] = 'checked';
                    }            
                }
                // @codeCoverageIgnoreEnd
            }
        }

        return $value;
    } 
}
