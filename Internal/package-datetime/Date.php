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

use ZN\Datatype;

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
        if( in_array($method, $this->isDays) )
        {
            return $this->isDay($method, $parameters[0] ?? NULL);
        }
        elseif( in_array($method, $this->isMonths) )
        {
            return $this->isMonth($method, $parameters[0] ?? NULL);
        }
    }

    /**
     * Gives the active date information.
     * 
     * @param string $clock = '%H:%M:%S'
     * 
     * @return string
     */
    public function current(String $clock = 'd.m.o') : String
    {
        return $this->_datetime($clock);
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
     * Checks whether the date is the weekend.
     * 
     * 5.7.6[added]
     * 
     * @param string $date
     * 
     * @return bool
     */
    public function isWeekend(String $date) : Bool
    {
        $weekDayNumber = $this->convert($date, '{weekDayNumber}');

        return in_array($weekDayNumber, [6, 7]);
    }

    /**
     * Give it today.
     * 
     * @return string
     */
    public function today(String $date = NULL) : String
    {
        $type = '{dayName}';

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
    public function todayNumber() : String
    {
        return $this->set('{dayNumber}');
    }

    /**
     * Checks whether the today is the weekend.
     * 
     * 5.7.6[added]
     * 
     * @return bool
     */
    public function todayIsWeekend() : Bool
    {
        $weekDayNumber = $this->set('{weekDayNumber}');

        return in_array($weekDayNumber, [6, 7]);
    }

    /**
     * Get next day name.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function nextDay(String $next = '1') : String
    {
        return $this->next($next, 'day');
    }

    /**
     * Get prev day name.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function prevDay(String $next = '1') : String
    {
        return $this->prev($next, 'day');
    }

    /**
     * Get next month name.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function nextMonth(String $next = '1') : String
    {
        return $this->next($next, 'month', 'month');
    }

    /**
     * Get current month name.
     * 
     * 5.7.6[added]
     * 
     * @return string
     */
    public function currentMonth() : String
    {
        return $this->convert($this->current(), '{month}');
    }

    /**
     * Get prev month name.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function prevMonth(String $next = '1') : String
    {
        return $this->prev($next, 'month', 'month');
    }

    /**
     * Get next month number.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function nextMonthNumber(String $next = '1') : String
    {
        return $this->next($next, 'monthNumber', 'month');
    }

    /**
     * Get current month number.
     * 
     * 5.7.6[added]
     * 
     * @return string
     */
    public function currentMonthNumber() : String
    {
        return $this->convert($this->current(), '{monthNumber}');
    }

    /**
     * Get prev month number.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function prevMonthNumber(String $next = '1') : String
    {
        return $this->prev($next, 'monthNumber', 'month');
    }

    /**
     * Get next year.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function nextYear(String $next = '1') : String
    {
        return $this->next($next, 'year', 'year');
    }

    /**
     * Get prev year.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function prevYear(String $next = '1') : String
    {
        return $this->prev($next, 'year', 'year');
    }

    /**
     * Get yesterday.
     * 
     * 5.7.6[added]
     * 
     * @return string
     */
    public function yesterday() : String
    {
        return $this->prev(1, 'day');
    }

    /**
     * Get yesterday.
     * 
     * 5.7.6[added]
     * 
     * @return string
     */
    public function tomorrow() : String
    {
        return $this->next(1, 'day');
    }

    /**
     * Get next day number.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function nextDayNumber(String $next = '1', $type = 'dayNumber') : String
    {
        return $this->next($next, 'dayNumber');
    }

    /**
     * Get prev day number.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function prevDayNumber(String $next = '1') : String
    {
        return $this->prev($next, 'dayNumber');
    }

    /**
     * Protected next
     */
    protected function next(String $next = '1', $type = 'day', $unit = 'day', $signal = '+') : String
    {
        $calculate = $this->calculate($this->current(), $signal . $next . $unit, 'Y/m/d');

        return $this->convert($calculate, '{'.$type.'}');
    }

    /**
     * Protected prev
     */
    protected function prev(String $next = '1', $type = 'day' , $unit = 'day') : String
    {
        return $this->next($next, $type, $unit, '-');
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
        return $this->convert($date ?? $this->current(), '{month}') === ltrim($method, 'is');
    }
}
