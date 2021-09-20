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

use ZN\Hypertext\Exception\InvalidArgumentException;
use ZN\DataTypes\Arrays;
use ZN\Protection\Json;
use ZN\Request\Method;
use ZN\Buffering;
use ZN\Singleton;
use ZN\Inclusion;
use ZN\Base;

class Form
{
    use ViewCommonTrait;

    /**
     * Keeps validation usage this form info.
     * 
     * @var bool
     */
    protected $validateUsageThisForm = false;

    /**
     * Keeps form name.
     * 
     * @var string
     */
    protected $getValidationFormName = NULL;

    /**
     * Keeps form input objects.
     * 
     * @var array
     */
    protected $elements =
    [
        'input' =>
        [
            'button', 'reset' , 'submit'  , 'radio', 'checkbox',
            'date'  , 'time'  , 'datetime', 'week' , 'month'   ,
            'text'  , 'search', 'password', 'email', 'tel'     ,
            'number', 'url'   , 'range'   , 'image', 'color'
        ]
    ];

    /**
     * Keeps validation rules.
     * 
     * @var array
     */
    protected $validate = [];

    /**
     * Keeps method type.
     * 
     * @var string
     */
    protected $method;

    /**
     * Keeps update process row
     * 
     * @var object
     */
    protected $getUpdateRow;

    /**
     * Gets update process row
     */
    public function getUpdateRow()
    {
        return $this->getUpdateRow;
    }

    /**
     * Open form tag.
     * 
     * @param string $name        = NULL
     * @param array  $_attributes = []
     * 
     * Available Enctype Options
     * 
     * 1. multipart   => multipart/form-data
     * 2. application => application/x-www-form-urlencoded
     * 3. text        => text/plain
     * 
     * @return string|object
     */
    public function open(string $name = NULL, array $_attributes = [])
    {
        $this->setFormName($name, $_attributes);

        $this->isEnctypeAttribute($_attributes);
       
        $this->isWhereAttribute($name);
        
        $this->isQueryAttribute();

        $this->isPreventAttribute();
        
        $this->setMethodType($_attributes);
        
        $this->createFormElementByAttributes($_attributes, $return);

        $this->isDatabaseProcessWithName($name, $return);

        $this->isCSRFAttribute($return);

        $this->_unsetopen();

        $this->outputElement .= $return;

        return $this;
    }

    /**
     * Validate error message.
     * 
     * @param void
     * 
     * @return string
     */
    public function validateErrorMessage()
    {
        return Singleton::class('ZN\Validation\Data')->error('string');
    }

    /**
     * Validate error array.
     * 
     * @param void
     * 
     * @return array
     */
    public function validateErrorArray()
    {
        return Singleton::class('ZN\Validation\Data')->error('array');
    }

    /**
     * Reset validation rules
     * 
     * @param string $formName
     */
    public function resetValidationRules(string $formName)
    {
        $session = Singleton::class('ZN\Storage\Session');

        $session->delete('FormValidationRules' . $formName);
        $session->delete('FormValidationMethod' . $formName);

        return $this;
    }

    /**
     * Closes form object.
     * 
     * @param void
     * 
     * @return string|object
     */
    public function close()
    {
        unset($this->settings['getrow']);

        if( isset($this->getJavascriptValidationFunction) )
        {
            $this->outputElement .= Inclusion\View::use('JavascriptValidationFunctions', $this->getJavascriptValidationFunction, true, __DIR__ . '/');

            $this->getJavascriptValidationFunction = NULL;
        }

        if( $this->validateUsageThisForm === true )
        {
            $this->outputElement .= '<input type="hidden" name="ValidationFormName" value="' . $this->getValidationFormName . '">';

            $this->getValidationFormName = NULL;

            $this->validateUsageThisForm = false;
        }
        
        $this->outputElement .= '</form>' . EOL;
        

        return $this;
    }

    /**
     * datetime-local form object.
     * 
     * @param string $name        = NULL
     * @param string $value       = NULL
     * @param array  $_attributes = []
     * 
     * @return string|object
     */
    public function datetimeLocal(string $name = NULL, string $value = NULL, array $_attributes = [])
    {
        return $this->_input($name, $value, $_attributes, 'datetime-local');
    }

    /**
     * textarea form object.
     * 
     * @param string $name        = NULL
     * @param string $value       = NULL
     * @param array  $_attributes = []
     * 
     * @return string|object
     */
    public function textarea(string $name = NULL, string $value = NULL, array $_attributes = [])
    {
        $this->setNameAttribute($name);

        $this->setValueAttribute($value);

        if( ! empty($this->settings['attr']['name']) )
        {
            $this->_postback($this->settings['attr']['name'], $value);

            # 5.8.2.8[added]
            $this->getVMethodMessages();

            # 5.4.2[added]
            $this->_validate($this->settings['attr']['name'], $this->settings['attr']['name']);
            
            # 5.4.2[added]|5.4.5|5.4.6[edited]
            $value = $this->_getrow('textarea', $value, $this->settings['attr']);
        }

        $this->commonMethodsForInputElements('textarea');

        $this->getPermAttribute($perm);

        $this->createTextareaElementByValueAndAttributes($value, $_attributes, $return);

        $this->createBootstrapFormInputElementByType('textarea', $return, $_attributes, $return);

        $this->outputElement .= $this->_perm($perm, $return);

        return $this;
    }

    /**
     * select form object.
     * 
     * @param string $name        = NULL
     * @param string $optios      = []
     * @param mixed  $selected    = NULL
     * @param array  $_attributes = []
     * @param bool   $multiple    = false
     * 
     * @return string|object
     */
    public function select(string $name = NULL, array $options = [], $selected = NULL, array $_attributes = [], bool $multiple = false)
    {
        $this->isRepeatData($options);

        $this->isTableOrQueryData($options);

        $this->setOptionAttribute($options);

        $this->isExcludeAttribute($options);

        $this->isIncludeAttribute($options);

        $this->isOrderAttribute($options);

        $this->setSelectedAttribute($selected, $options);

        $this->setMultipleAttribute($multiple, $_attributes);

        $this->setNameAttributeWithReference($name, $_attributes);

        if( ! empty($_attributes['name']) )
        {
            $this->_postback($_attributes['name'], $selected);

            # 5.8.2.8[added]
            $this->getVMethodMessages();

            # 5.4.2[added]
            $this->_validate($_attributes['name'], $_attributes['name']);
            
            # 5.4.2[added]|5.4.5|5.4.6[edited]
            $selected = $this->_getrow('select', $selected, $_attributes);
        }

        $this->commonMethodsForInputElements('select');

        $this->getPermAttribute($perm);

        $this->createSelectElement($options, $selected, $_attributes, $return);

        $this->createBootstrapFormInputElementByType('select', $return, $_attributes, $return);

        $this->_unsetselect();

        $this->outputElement .= $this->_perm($perm, $return);

        return $this;
    }

    /**
     * select type multiselect form object.
     * 
     * @param string $name        = NULL
     * @param string $optios      = []
     * @param mixed  $selected    = NULL
     * @param array  $_attributes = []
     * 
     * @return string|object
     */
    public function multiselect(string $name = NULL, array $options = [], $selected = NULL, array $_attributes = [])
    {
        return $this->select($name, $options, $selected, $_attributes, true);
    }

    /**
     * hidden form object.
     * 
     * @param string $name        = NULL
     * @param string $value       = NULL
     * 
     * @return string
     */
    public function hidden($name = NULL, string $value = NULL)
    {
        $name  = $this->settings['attr']['name' ] ?? $name ;
        $value = $this->settings['attr']['value'] ?? $value;

        $this->settings['attr'] = [];

        $hiddens = NULL;
        
        if( is_array($name) ) foreach( $name as $key => $val )
        {
            $hiddens .= $this->createHiddenElement($key, $val);
        }
        else
        {
            $hiddens =  $this->createHiddenElement($name, $value);
        }

        $this->outputElement .= $hiddens;

        return $this;
    }

    /**
     * file form object.
     * 
     * @param string $name        = NULL
     * @param string $value       = NULL
     * @param array  $_attributes = []
     * 
     * @return string|object
     */
    public function file(string $name = NULL, bool $multiple = false, array $_attributes = [])
    {
        if( ! empty($this->settings['attr']['multiple']) )
        {
            $multiple = true;
        }

        $name = $this->settings['attr']['name'] ?? $name;

        if( $multiple === true )
        {
            $this->settings['attr']['multiple'] = 'multiple';
            $name = Base::suffix($name, '[]');
        }

        $this->commonMethodsForInputElements('file');

        return $this->_input($name, '', $_attributes, 'file');
    }

    /**
     * Protected create hidden element
     */
    protected function createHiddenElement($key, $value)
    {
        return '<input type="hidden" name="' . $key . '" id="' . $key . '" value="' . $value . '">' . EOL;
    }

    /**
    * Protected create select element
    */
    protected function createSelectElement($options, $selected, $_attributes, &$return)
    {        
        $option = '';

        if( is_string($selected) && Json::check($selected) )
        {
            $selected = Json::decodeArray($selected);
        }

        if( is_array($options) ) foreach( $options as $key => $value )
        {
            if( is_array($selected) )
            {
                if( in_array($key, $selected) )
                {
                    $select = ' selected="selected"';
                }
                else
                {
                    $select = "";
                }
            }
            else
            {
                if( $selected === $key || ( is_numeric($selected) && $selected == $key ) )
                {
                    $select = ' selected="selected"';
                }
                else
                {
                    $select = "";
                }
            }

            if( is_numeric($value) || ! empty($value) )
            {
                $option .= '<option value="'.$key.'"'.$select.'>'.$value.'</option>'.EOL;
            }
        }

        if( isset($this->settings['attr']['only-options']) )
        {
            unset($this->settings['attr']['only-options']);
            
            $return = $option;
        }
        else
        {
            $return = '<select'.$this->attributes($_attributes).'>' . $option . '</select>'.EOL;
        }
    }

    /**
     * Protected set multiple attribute
     */
    protected function setMultipleAttribute($multiple, &$_attributes)
    {
        if( $multiple === true )
        {
            $_attributes['multiple'] = 'multiple';
        }
    }

    /**
     * Protected set selected attribute
     */
    protected function setSelectedAttribute(&$selected, $options)
    {
        $selected = $this->settings['selectedKey'] ?? $selected;

        if( isset($this->settings['selectedValue']) )
        {
            $flip     = array_flip($options);
            $selected = $flip[$this->settings['selectedValue']];
        }
    }

    /**
     * Protected is order attribute
     */
    protected function isOrderAttribute(&$options)
    {
        if( isset($this->settings['order']['type']) )
        {
            $options = Arrays\Sort::order($options, $this->settings['order']['type'], $this->settings['order']['flags']);
        }
    }

    /**
     * Protected is exclude attribute
     */
    protected function isExcludeAttribute(&$options)
    {
        if( isset($this->settings['exclude']) )
        {
            $options = Arrays\Excluding::use($options, $this->settings['exclude']);
        }
    }

    /**
     * Protected is include attribute
     */
    protected function isIncludeAttribute(&$options)
    {
        if( isset($this->settings['include']) )
        {
            $options = Arrays\Including::use($options, $this->settings['include']);
        }
    }

    /**
     * Protected set option attribute
     */
    protected function setOptionAttribute(&$options)
    {
        $options = $this->settings['option'] ?? $options;
    }

    /**
     * Protected is repeat data
     */
    protected function isRepeatData(&$options)
    {
        if( ! empty($this->settings['attr']['repeat']) )
        {
            $key = key($options); $current = current($options);

            if( $key > $current )
            {
                $ocurrent = $current;
                $current  = $key;
                $key      = $ocurrent;
            }

            for( $i = $key; $i <= $current; $i++ )
            {
                $options[$i] = $i;
            }

            unset($this->settings['attr']['repeat']);
        }
    }

    /**
     * Protected is table or query data
     */
    protected function isTableOrQueryData(&$options)
    {
        if( ! empty($this->settings['table']) || ! empty($this->settings['query']) )
        {
            $key     = key($options);
            $current = current($options);

            if( is_callable($current) )
            {
                $selectedColumns = ['*'];
            }
            else
            {
                $selectedColumns = [$key, $current];
            }
            
            array_shift($options);

            $dbClass = Singleton::class('ZN\Database\DB');

            if( ! empty($this->settings['table']) )
            {
                $table = $this->settings['table'];

                if( strstr($table, ':') )
                {
                    $tableEx = explode(':', $tableEx);
                    $table   = $tableEx[1];
                    $db      = $tableEx[0];

                    $db     = $dbClass->differentConnection($db);
                    $result = $db->select(...$selectedColumns)->get($table)->result();
                }
                else
                {
                    $result = $dbClass->select(...$selectedColumns)->get($table)->result();
                }
            }
            else
            {
                $result = $dbClass->query($this->settings['query'])->result();
            }

            foreach( $result as $row )
            {
                if( is_callable($current) )
                {
                    $options[$row->$key] = $current($row);
                }
                else
                {
                    $options[$row->$key] = $row->$current;
                }
            }
        }
    }

    /**
     * Protected create textarea element by value and attributes
     */
    protected function createTextareaElementByValueAndAttributes($value, $_attributes, &$return)
    {
        $return = '<textarea'.$this->attributes($_attributes).'>'.$value.'</textarea>' . EOL;
    }

    /**
     * Protected set textarea name attribute
     */
    protected function setNameAttribute($name)
    {
        if( ! isset($this->settings['attr']['name']) && ! empty($name) )
        {
            $this->settings['attr']['name'] = $name;
        }
    }

    /**
     * Protected set value attribute
     */
    protected function setValueAttribute(&$value)
    {
        $value = $this->settings['attr']['value'] ?? $value;
    }

    /**
     * Protected set form name
     */
    protected function setFormName(&$name, &$_attributes)
    {
        $this->getValidationFormName = $name = $this->settings['attr']['name'] ?? $name;
  
        $_attributes['name'] = $name;
    }

    /**
     * Protected create form element by attributes
     */
    protected function createFormElementByAttributes($_attributes, &$return)
    {
        $this->changeFormAttributes
        ([
            'inline'     => 'class:form-inline',
            'horizontal' => 'class:form-horizontal'
        ]);

        $return = '<form'.$this->attributes($_attributes).'>' . EOL;
    }

    /**
     * Protected is database process with name
     */
    protected function isDatabaseProcessWithName($name, &$return)
    {
        $return .= $this->_process($name, $this->method);
    }

    /**
     * Protected is csrf attribute
     */
    protected function isCSRFAttribute(&$return)
    {
        if( isset($this->settings['token']) )
        {
            $return .= CSRFInput();
        }
    }

    /**
     * Protected set method type
     */
    protected function setMethodType(&$_attributes)
    {
        $this->method = ($_attributes['method'] = $_attributes['method'] ?? $this->settings['attr']['method'] ?? 'post');
    }

    /**
     * Protected is enctype attribute
     */
    protected function isEnctypeAttribute(&$_attributes)
    {
        if( isset($_attributes['enctype']) )
        {
            $enctype = $_attributes['enctype'];

            if( isset($this->enctypes[$enctype]) )
            {
                $_attributes['enctype'] = $this->enctypes[$enctype];
            }
        }
    }

    /**
     * Protected is where attribute
     */
    protected function isWhereAttribute($name)
    {
        if( isset($this->settings['where']) )
        {
            $this->settings['getrow'] = Singleton::class('ZN\Database\DB')->get($name)->row();
        }
    }

    /**
     * Protected is query attribute
     */
    protected function isQueryAttribute()
    {
        if( $query = ($this->settings['query'] ?? NULL) )
        {
            $this->settings['getrow'] = Singleton::class('ZN\Database\DB')->query($query)->row();
        }
    }

    /**
     * Protected is prevent attribute
     */
    protected function isPreventAttribute()
    {
        if( isset($this->settings['attr']['prevent']) )
        {
            unset($this->settings['attr']['prevent']);

            $this->settings['attr']['onsubmit'] = 'event.preventDefault()';
        }
    }

    /**
     * protected process
     * 
     * @param string $name
     * @param string $method
     * 
     * @return mixed
     */
    protected function _process($name, $method)
    {
        if( $process = ($this->settings['process'] ?? NULL) )
        {
            if( Method::$method('FormProcessValue') )
            {
                if( Singleton::class('ZN\Validation\Data')->check() )
                {
                    $dbClass = Singleton::class('ZN\Database\DB');

                    if( $process === 'update' )
                    {
                        $dbClass->where
                        (
                            $whereColumn = $this->settings['whereColumn'], 
                            $whereValue  = $this->settings['whereValue']
                        )
                        ->update(strtolower($method).':'.$name);       

                        $this->getUpdateRow = $this->settings['getrow'] = $dbClass->where($whereColumn, $whereValue)->get($name)->row();
                    }
                    elseif( $process === 'insert' )
                    {
                        if( isset($this->settings['duplicateCheck']) )
                        {
                            $dbClass->duplicateCheck();
                        }

                        $dbClass->insert(strtolower($method).':'.$name); 
                    }
                    else
                    {
                        throw new InvalidArgumentException('[Form::process()] method can take one of the values [update or insert].');
                    }
                }
            }

            return (string) $this->hidden('FormProcessValue', 'FormProcessValue');
        }
    }

    /**
     * protected unset select variables
     * 
     * @param void
     * 
     * @return void
     */
    protected function _unsetselect()
    {
        unset($this->settings['table']);
        unset($this->settings['query']);
        unset($this->settings['option']);
        unset($this->settings['exclude']);
        unset($this->settings['include']);
        unset($this->settings['order']);
        unset($this->settings['selectedKey']);
        unset($this->settings['selectedValue']);
    }

    /**
     * protected unset open variables
     * 
     * @param void
     * 
     * @return void
     */
    protected function _unsetopen()
    {
        unset($this->settings['where']);
        unset($this->settings['whereValue']);
        unset($this->settings['whereColumn']);
        unset($this->settings['query']);
        unset($this->settings['token']);
        unset($this->settings['process']);
        unset($this->settings['duplicateCheck']);
    }
}
