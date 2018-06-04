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
    public function check(String $time) : Bool
    {
        $timeEx    = explode(':', $this->convert($time, '{hour}:{minute}:{second}'));
        $validTime = implode('/', $timeEx);
        
        if( $time !== $validTime && $validTime === '01/00/00' )
        {
            return false;
        }

        return $this->checktime($timeEx[0] ?? NULL, $timeEx[1] ?? NULL, $timeEx[2] ?? NULL);
    }

    /**
     * Is past
     * 
     * @string $time
     * 
     * @return bool
     */
    public function isPast(String $time) : Bool
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
    public function current(String $clock = '%H:%M:%S') : String
    {
        return $this->_datetime($clock);
    }

    /**
     * Gives the active date information.
     * 
     * @param string $clock = '%H:%M:%S'
     * 
     * @return string
     */
    public function default(String $time = '{hour}:{minute}:{second}') : String
    {
        return $this->_datetime($time);
    }

    /**
     * Converts date information.
     * 
     * @param string $date
     * @param string $format = '%d-%B-%Y %A, %H:%M:%S'
     * 
     * @return string
     */
    public function convert(String $date, String $format = '%d-%B-%Y %A, %H:%M:%S') : String
    {
        return $this->_datetime($format, strtotime($date));
    }

    /**
     * Generates standard date and time information.
     * 
     * @return string
     */
    public function standart() : String
    {
        return strftime("%d %B %Y %A, %H:%M:%S");
    }

    /**
     * Protected next
     */
    protected function next(String $time = NULL, $type = 'hour', $signal = '+') : String
    {
        $calculate = $this->calculate($time ?? $this->default(), $signal . '1' . $type);

        return $this->convert($calculate, '{'.$type.'}');
    }

    /**
     * Protected prev
     */
    protected function prev(String $time = NULL, $type = 'hour') : String
    {
        return $this->next($time, $type, '-');
    }

    /**
     * Protected checktime
     */
    protected function checktime($hour, $min, $sec) 
    {
        if
        (
            ($hour < 0 || $hour > 23 || ! is_numeric($hour)) ||
            ($min  < 0 || $min  > 59 || ! is_numeric($min))  ||
            ($sec  < 0 || $sec  > 59 || ! is_numeric($sec)) 
        ) 
        {
            return false;
        }

        return true;
   }
   
}
