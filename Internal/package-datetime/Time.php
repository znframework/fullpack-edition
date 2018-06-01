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
     * Get next hour.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function nextHour(String $next = '1') : String
    {
        return $this->next($next, 'hour');
    }

    /**
     * Get prev hour.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function prevHour(String $next = '1') : String
    {
        return $this->prev($next, 'hour');
    }

    /**
     * Get current hour.
     * 
     * 5.7.6[added]
     * 
     * @return string
     */
    public function currentHour() : String
    {
        return $this->set('{hour}');
    }

    /**
     * Get next minute.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function nextMinute(String $next = '1') : String
    {
        return $this->next($next, 'minute');
    }

    /**
     * Get prev minute.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function prevMinute(String $next = '1') : String
    {
        return $this->prev($next, 'minute');
    }

    /**
     * Get current minute.
     * 
     * 5.7.6[added]
     * 
     * @return string
     */
    public function currentMinute() : String
    {
        return $this->set('{minute}');
    }

    /**
     * Get next second.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function nextSecond(String $next = '1') : String
    {
        return $this->next($next, 'second');
    }

    /**
     * Get prev second.
     * 
     * 5.7.6[added]
     * 
     * @param string $next = 1
     * 
     * @return string
     */
    public function prevSecond(String $next = '1') : String
    {
        return $this->prev($next, 'second');
    }

    /**
     * Get current second.
     * 
     * 5.7.6[added]
     * 
     * @return string
     */
    public function currentSecond() : String
    {
        return $this->set('{second}');
    }

    /**
     * Protected next
     */
    public function next(String $next = '1', $type = 'hour', $signal = '+') : String
    {
        $calculate = $this->calculate($this->current(), $signal . $next . $type);

        return $this->convert($calculate, '{'.$type.'}');
    }

    /**
     * Protected prev
     */
    public function prev(String $next = '1', $type = 'hour') : String
    {
        return $this->next($next, $type, '-');
    }
}
