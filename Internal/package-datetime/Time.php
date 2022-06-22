<?php namespace ZN\DateTime;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Time extends DateTimeCommon implements DateTimeCommonInterface
{
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
        $parts = $this->splitUpperCase($method);
        
        $methodType = $parts[0] ?? NULL;

        if( in_array($methodType, ['next', 'prev']) )
        {
            return $this->$methodType($parameters[0] ?? NULL, ltrim($method, $methodType));
        }

        return parent::__call($method, $parameters);
    }

    /**
     * Date check
     * 
     * @param string $time
     * 
     * @return bool
     */
    public function check(string $time) : bool
    {
        return (new Date)->check($time);
    }

    /**
     * Is past
     * 
     * @string $time
     * 
     * @return bool
     */
    public function isPast(string $time) : bool
    {
        return $this->compare($time, '<', $this->set('{hour}:{minute}:{second}'));
    }

    /**
     * Gives the active time information.
     * 
     * @param string $clock = '%H:%M:%S'
     * 
     * @return string
     */
    public function current(string $clock = 'H:i:s') : string
    {
        return $this->returnDatetime($clock);
    }

    /**
     * Gives the active date information.
     * 
     * @param string $clock = '%H:%M:%S'
     * 
     * @return string
     */
    public function default(string $time = '{hour}:{minute}:{second}') : string
    {
        return $this->returnDatetime($time);
    }

    /**
     * Converts date information.
     * 
     * @param string $date
     * @param string $format = '%d-%B-%Y %A, %H:%M:%S'
     * 
     * @return string
     */
    public function convert(string $date, string $format = 'd-m-Y H:i:s') : string
    {
        return $this->returnDatetime($format, strtotime($date));
    }

    /**
     * Generates standard date and time information.
     * 
     * @return string
     */
    public function standart() : string
    {
        return (new Date)->standart();
    }

    /**
     * Protected next
     */
    protected function next(string $time = NULL, $type = 'hour', $signal = '+') : string
    {
        $calculate = $this->calculate($time ?? $this->default(), $signal . '1' . $type);

        return $this->convert($calculate, '{'.$type.'}');
    }

    /**
     * Protected prev
     */
    protected function prev(string $time = NULL, $type = 'hour') : string
    {
        return $this->next($time, $type, '-');
    }   
}
