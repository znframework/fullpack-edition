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

class Date extends DateTimeCommon implements DateTimeCommonInterface
{
    /**
     * Protected is days
     * 
     * @var array
     */
    protected $isDays = 
    [
        'isSunday', 
        'isMonday', 
        'isTuesday', 
        'isWednesday', 
        'isThursday', 
        'isFriday', 
        'isSaturday'
    ];

    /**
     * Protected is months
     * 
     * @var array
     */
    protected $isMonths = 
    [
        'isJanuary', 
        'isFebruary', 
        'isMarch', 
        'isApril', 
        'isMay', 
        'isJune', 
        'isJuly', 
        'isAugust', 
        'isSeptember', 
        'isOctober', 
        'isNovember', 
        'isDecember'
    ];

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

        if( in_array($method, $this->isDays) )
        {
            return $this->isDay($method, $parameters[0] ?? NULL);
        }
        elseif( in_array($method, $this->isMonths) )
        {
            return $this->isMonth($method, $parameters[0] ?? NULL);
        }
        elseif( in_array($methodType, ['next', 'prev']) )
        {
            return $this->$methodType($parameters[0] ?? NULL, ($type = strtolower($parts[1])) . ($parts[2] ?? NULL), $type);
        }

        return parent::__call($method, $parameters);
    }

    /**
     * Gets current datetime.
     * 
     * @return string
     */
    public function now()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Date check
     * 
     * @param string $date
     * 
     * @return bool
     */
    public function check(String $date) : Bool
    {
        $dateEx    = explode('/', $this->convert($date, '{year}/{monthNumber}/{dayNumber}'));
        $validDate = implode('/', $dateEx);

        if( $date !== $validDate && $validDate === '1970/1/1' )
        {
            return false;
        }

        return checkdate($dateEx[1] ?? NULL, $dateEx[2] ?? NULL, $dateEx[0] ?? NULL);

       
    }

    /**
     * Gives the active date information.
     * 
     * @param string $clock = '%H:%M:%S'
     * 
     * @return string
     */
    public function current(String $date = 'd.m.o') : String
    {
        return $this->_datetime($date);
    }

    /**
     * Gives the active date information.
     * 
     * @param string $clock = '%H:%M:%S'
     * 
     * @return string
     */
    public function default(String $date = '{year}/{monthNumber0}/{dayNumber0}') : String
    {
        return $this->_datetime($date);
    }

    /**
     * Converts date information.
     * 
     * @param string $date
     * @param string $format = '%d-%B-%Y %A, %H:%M:%S'
     * 
     * @return string
     */
    public function convert(String $date, String $format = 'd-m-Y H:i:s') : String
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
        return $this->_datetime("d.F.o l, H:i:s");
    }

    /**
     * Is past
     * 
     * @string $date
     * 
     * @return bool
     */
    public function isPast(String $date) : Bool
    {
        return $this->compare($date, '<', $this->set('Y/m/d'));
    }

    /**
     * Checks whether the date is the weekend.
     * 
     * 5.7.6[added]
     * 
     * @param string $date
     * 
     * @return bool
     */
    public function isWeekend(String $date = NULL) : Bool
    {
        $weekDayNumber = $this->convert($date ?? $this->default(), '{weekDayNumber}');

        return in_array($weekDayNumber, [6, 7]);
    }

    /**
     * Give it today.
     * 
     * @return string
     */
    public function today(String $date = NULL, $type = 'dayName') : String
    {
        $type = '{'.$type.'}';

        if( $date === NULL )
        {
            return $this->set($type);
        }
        
        return $this->convert($date, $type);
    }

    /**
     * Give it today day number.
     * 
     * @return string
     */
    public function todayNumber(String $date = NULL) : String
    {
        return $this->today($date, 'dayNumber');
    }

    /**
     * Get yesterday.
     * 
     * 5.7.6[added]
     * 
     * @param string $date = NULL
     * 
     * @return string
     */
    public function yesterday(String $date = NULL) : String
    {
        return $this->prev($date, 'day');
    }

    /**
     * Get yesterday.
     * 
     * 5.7.6[added]
     * 
     * @param string $date = NULL
     * 
     * @return string
     */
    public function tomorrow(String $date = NULL) : String
    {
        return $this->next($date, 'day');
    }

    /**
     * Protected next
     */
    protected function next(String $date = NULL, $type = 'day', $unit = 'day', $signal = '+') : String
    {
        $calculate = $this->calculate($date ?? $this->default(), $signal . '1' . $unit, 'Y/m/d');

        return $this->convert($calculate, '{'.$type.'}');
    }

    /**
     * Protected prev
     */
    protected function prev(String $date = NULL, $type = 'day' , $unit = 'day') : String
    {
        return $this->next($date, $type, $unit, '-');
    }

    /**
     * Protected is day for call method
     */
    protected function isDay($method, $date)
    {
        return $this->today($date) === ltrim($method, 'is');
    }

    /**
     * Protected is day for call method
     */
    protected function isMonth($method, $date)
    {
        return $this->convert($date ?? $this->default(), '{month}') === ltrim($method, 'is');
    }
}
