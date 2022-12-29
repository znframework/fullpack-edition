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

trait Serialization
{
    /**
     * Magic set
     * 
     * @param string $propery
     * @param mixed  $value
     * 
     * @return void
     */
    protected function __set($property, $value)
    {
        $this->$property = $value;
    }

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
        # Gets lower method name.
        $lowerMethodName = strtolower($method);

        # Gets serialization class name.
        $class = self::serialization['class'];

        # Gets process type.
        # If operation type data is selected, operation is continued on the value sent as parameter.
        # Otherwise, the operation continues on the last value returned from the parameter being processed.
        $process = (self::serialization['process'] ?? 'serial') === 'serial' ? 'data' : 'return'; 

        # The name of the first method that holds the data to be processed.
        if( $lowerMethodName === self::serialization['start'] )
        {
            $this->data = $parameters[0];
        }
        # The name of the final method to complete the process flow.
        elseif( $lowerMethodName === self::serialization['end'] )
        {
            return $this->$process;
        }
        # Otherwise, the invoked other class methods are executed.
        else
        {
            $this->$process = $class::$method($this->data, ...$parameters);
        }

        return $this;
    }
}
