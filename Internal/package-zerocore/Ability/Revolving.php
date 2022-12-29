<?php namespace ZN\Ability;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

trait Revolving
{
    /**
     * Get revolving values
     * 
     * @var array
     */
    protected $revolvings;

    /**
     * Magic call
     * 
     * @param string $method
     * @param array  $param
     * 
     * @return $this
     */
    public function __call($method, $param)
    {  
        # It opens the way for you to use multiple magic call methods.
        if( defined('static::call') )
        {
            if( $return = $this->{static::call}($method, $param) )
            {
                return $return;
            }
        }
     
        $this->$method = (count($param ?? []) > 1) ? $param : ($param[0] ?? NULL);
        
        $this->revolvings[$method] = $this->$method;

        return $this;
    }

    /**
     * Magic call static
     * 
     * @param string $method
     * @param array  $param
     * 
     * @return self
     */
    public static function __callStatic($method, $param)
    {
        return (new self)->__call($method, $param);
    }

    /**
     * Default variables
     * 
     * @param string $type = 'all'
     * @param bool   $self = false
     * 
     * @return void
     */
    protected function defaultVariables($type = 'all', $self = false)
    {
        # Gets class variables.
        $vars = $this->getClassVarsByType($type);      

        # MDefaults all class properties null.
        foreach( $vars as $key => $var )
        {
            $this->$key = ($self === false ? NULL : $var);
        }
    }

    /**
     * Protected get class vars
     */
    protected function getClassVarsByType($type)
    {
        return $type === 'all' ? get_class_vars(get_called_class()) : $this->revolvings;  
    }

    /**
     * Default revolving variables
     * 
     * @param void
     * 
     * @return void
     */
    protected function defaultRevolvingVariables()
    {
        # It only converts the properties that this property creates to a null value.
        $this->defaultRevolvings('revolving');
    }
}
