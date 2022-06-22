<?php namespace ZN\Crontab;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Base;
use ZN\Crontab\Exception\InvalidTimeFormatException;

trait CrontabIntervalTrait
{
    /**
     * Interval
     * 
     * @var string
     */
    protected $interval = '* * * * *';

    /**
     * Minute
     * 
     * @var string
     */
    protected $minute = '*';

    /**
     * Hour
     * 
     * @var string
     */
    protected $hour = '*';

    /**
     * Day Number
     * 
     * @var string
     */
    protected $dayNumber = '*';

    /**
     * Month
     * 
     * @var string
     */
    protected $month = '*';

    /**
     * Day
     * 
     * @var string
     */
    protected $day = '*';

    /**
     * Month Format
     * 
     * @var array
     */
    protected $monthFormat =
    [
        'january'   => 1,
        'february'  => 2,
        'march'     => 3,
        'april'     => 4,
        'may'       => 5,
        'june'      => 6,
        'july'      => 7,
        'august'    => 8,
        'september' => 9,
        'october'   => 10,
        'november'  => 11,
        'december'  => 12
    ];

    /**
     * Day Format
     * 
     * @var array
     */
    protected $dayFormat =
    [
        'sunday'    => 0,
        'monday'    => 1,
        'tuesday'   => 2,
        'wednesday' => 3,
        'thursday'  => 4,
        'friday'    => 5,
        'saturday'  => 6
    ];

    /**
     * Hourly
     * 
     * @return Job
     */
    public function hourly() : Job
    {
        $this->interval = '0 * * * *';

        return $this;
    }

    /**
     * Daily
     * 
     * @return Job
     */
    public function daily() : Job
    {
        $this->interval = '0 0 * * *';

        return $this;
    }

    /**
     * Midnight
     * 
     * @return Job
     */
    public function midnight() : Job
    {
        $this->daily();

        return $this;
    }

    /**
     * Monthly
     * 
     * @return Job
     */
    public function monthly() : Job
    {
        $this->interval = '0 0 1 * *';

        return $this;
    }

    /**
     * Weekly
     * 
     * @return Job
     */
    public function weekly() : Job
    {
        $this->interval = '0 0 * * 0';

        return $this;
    }

    /**
     * Yearly
     * 
     * @return Job
     */
    public function yearly() : Job
    {
        $this->interval = '0 0 1 1 *';

        return $this;
    }

    /**
     * Annualy
     * 
     * @return Job
     */
    public function annualy() : Job
    {
        $this->yearly();

        return $this;
    }

    /**
     * Clock
     * 
     * @param string $clock = '23:59'
     * 
     * @return Job
     */
    public function clock(string $clock = '23:59') : Job
    {
        if( ! preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/', $clock) )
        {
            throw new InvalidTimeFormatException(NULL, $clock);
        }
        else
        {
            $clockEx  = explode(':', $clock);

            $this->minute($clockEx[1]);
            $this->hour($clockEx[0]);
        }

        return $this;
    }

    /**
     * Minute
     * 
     * @param string $minute
     * 
     * @return Job
     */
    public function minute(string $minute) : Job
    {
        $this->minute = $this->_slashes($minute);

        return $this;
    }

    /**
     * Perminute
     * 
     * @param string $minute
     * 
     * @return Job
     */
    public function perMinute(string $minute) : Job
    {
        $this->_per($minute, 'minute');

        return $this;
    }

    /**
     * Hour
     * 
     * @param string $hour
     * 
     * @return Job
     */
    public function hour(string $hour) : Job
    {
        $this->hour = $this->_slashes($hour);

        return $this;
    }

    /**
     * Perhour
     * 
     * @param string $hour
     * 
     * @return Job
     */
    public function perHour(string $hour) : Job
    {
        $this->_per($hour, 'hour');

        return $this;
    }

    /**
     * Day Number
     * 
     * @param string $dayNumber
     * 
     * @return Job
     */
    public function dayNumber(string $dayNumber) : Job
    {
        $this->dayNumber = $this->_slashes($dayNumber);

        return $this;
    }

    /**
     * Month Number
     * 
     * @param string $monthNumber
     * 
     * @return Job
     */
    public function month(string $monthNumber) : Job
    {
        $this->_format('monthFormat', __FUNCTION__, $monthNumber );

        return $this;
    }

    /**
     * Permonth
     * 
     * @param string $month
     * 
     * @return Job
     */
    public function perMonth(string $month) : Job
    {
        $this->_per($month, 'month');

        return $this;
    }

    /**
     * Day
     * 
     * @param string $day= '*'
     * 
     * @return Job
     */
    public function day(string $day) : Job
    {
        $this->_format('dayFormat', __FUNCTION__, $day);

        return $this;
    }

    /**
     * Perday
     * 
     * @param string $day
     * 
     * @return Job
     */
    public function perDay(string $day) : Job
    {
        $this->_per($day, 'day');

        return $this;
    }

    /**
     * Interval
     * 
     * @param string $interval = '* * * * *'
     * 
     * @return Job
     */
    public function interval(string $interval = '* * * * *') : Job
    {
        $this->interval = $interval;

        return $this;
    }

    /**
     * Protected Time Format
     */
    protected function _format($varname, $objectname, $data)
    {
        $format      = $this->$varname;
        $replaceData = str_ireplace(array_keys($format), array_values($format), $data ?? '');

        $this->$objectname = $this->_slashes($replaceData);
    }

    /**
     * Protected Per
     */
    protected function _per($time, $function)
    {
        $this->$function(Base::prefix($time));
    }

    /**
     * Protected Slashes
     */
    protected function _slashes($data)
    {
        if( $data[0] === '/' )
        {
            return Base::prefix($data, '*');
        }

        return $data;
    }
}
