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
use ZN\Request\Method;
use ZN\Buffering;
use ZN\Singleton;
use ZN\Base;

class Form
{
    use ViewCommonTrait;

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
    public function open(String $name = NULL, Array $_attributes = [])
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
     * Closes form object.
     * 
     * @param void
     * 
     * @return string|object
     */
    public function close()
    {
        unset($this->settings['getrow']);

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
    public function datetimeLocal(String $name = NULL, String $value = NULL, Array $_attributes = [])
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
    public function textarea(String $name = NULL, String $value = NULL, Array $_attributes = [])
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
    public function select(String $name = NULL, Array $options = [], $selected = NULL, Array $_attributes = [], Bool $multiple = false)
    {
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
    public function multiselect(String $name = NULL, Array $options = [], $selected = NULL, Array $_attributes = [])
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
    public function hidden($name = NULL, String $value = NULL)
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
    public function file(String $name = NULL, Bool $multiple = false, Array $_attributes = [])
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
     * Use of bootstrap group
     * 
     * @param string|callable $code  = ''
     * @param string          $class = ''
     * 
     * @return string|this
     */
    public function group($code = '', String $class = '')
    {
        if( is_string($code) )
        {
            $this->settings['group']['class'] = $this->bootstrapClassResolution('form-group', $code);

            return $this;
        }
        elseif( is_callable($code) )
        {
            $this->callableGroup = true;

            $result = $this->getHTMLClass()
                           ->class('form-group row' . Base::prefix($class, ' '))
                           ->div(EOL . Buffering\Callback::do($code));

            unset($this->callableGroup);
            
            return $result;
        }
    }

    /**
     * Use of bootstrap label
     * 
     * @param string $for   = NULL
     * @param string $value = NULL
     * @param string $class = NULL
     * 
     * @return this
     */
    public function label(String $for = NULL, String $value = NULL, String $class = NULL)
    {
        $this->settings['label']['for'  ] = $for;
        $this->settings['label']['value'] = $value;
        $this->settings['label']['class'] = $class;

        return $this;
    }

    /**
     * Help text
     * 
     * @param string $content
     * @param string $class = NULL
     * 
     * @return this
     */
    public function helptext(String $content, String $class = '')
    {
        $this->settings['help']['text'] = $this->getHTMLClass()
                                               ->class('help-block' . Base::prefix($class, ' '))
                                               ->span($content);

        return $this;
    }   

    /**
     * Bootstrap col size
     * 
     * @param string $size
     * 
     * @return this
     */
    public function col(String $size)
    {
        $this->settings['col']['size'] = $size;

        return $this;
    }

    /**
     * Protected getHTMLClass
     */
    protected function getHTMLClass()
    {
        return Singleton::class('ZN\Hypertext\Html');
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
        $return = '<select'.$this->attributes($_attributes).'>';

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
                if( $selected == $key )
                {
                    $select = ' selected="selected"';
                }
                else
                {
                    $select = "";
                }
            }

            $return .= '<option value="'.$key.'"'.$select.'>'.$value.'</option>'.EOL;
        }

        $return .= '</select>'.EOL;
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
     * Protected is table or query data
     */
    protected function isTableOrQueryData(&$options)
    {
        if( ! empty($this->settings['table']) || ! empty($this->settings['query']) )
        {
            $key     = key($options);
            $current = current($options);
            
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
                    $result = $db->select($current, $key)->get($table)->result();
                }
                else
                {
                    $result = $dbClass->select($current, $key)->get($table)->result();
                }
            }
            else
            {
                $result = $dbClass->query($this->settings['query'])->result();
            }

            foreach( $result as $row )
            {
                $options[$row->$key] = $row->$current;
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
        $name = $this->settings['attr']['name'] ?? $name;
  
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

                        $this->settings['getrow'] = $dbClass->where($whereColumn, $whereValue)->get($name)->row();
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

            return $this->hidden('FormProcessValue', 'FormProcessValue');
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
