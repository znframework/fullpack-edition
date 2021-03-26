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

interface CrontabIntervalInterface
{
    /**
     * Hourly
     * 
     * @return Job
     */
    public function hourly() : Job;

    /**
     * Daily
     * 
     * @return Job
     */
    public function daily() : Job;

    /**
     * Midnight
     * 
     * @return Job
     */
    public function midnight() : Job;

    /**
     * Monthly
     * 
     * @return Job
     */
    public function monthly() : Job;

    /**
     * Weekly
     * 
     * @return Job
     */
    public function weekly() : Job;

    /**
     * Yearly
     * 
     * @return Job
     */
    public function yearly() : Job;

    /**
     * Annualy
     * 
     * @return Job
     */
    public function annualy() : Job;

   /**
    * Clock
    * 
    * @param string $clock = '23:59'
    * 
    * @return Job
    */
    public function clock(string $clock) : Job;

    /**
     * Minute
     * 
     * @param string $minute
     * 
     * @return Job
     */
    public function minute(string $minute) : Job;

    /**
     * Perminute
     * 
     * @param string $minute
     * 
     * @return Job
     */
    public function perMinute(string $minute) : Job;

    /**
     * Hour
     * 
     * @param string $hour
     * 
     * @return Job
     */
    public function hour(string $hour) : Job;

    /**
     * Perhour
     * 
     * @param string $hour
     * 
     * @return Job
     */
    public function perHour(string $hour) : Job;

    /**
     * Day Number
     * 
     * @param string $dayNumber
     * 
     * @return Job
     */
    public function dayNumber(string $dayNumber) : Job;

    /**
     * Month Number
     * 
     * @param string $monthNumber
     * 
     * @return Job
     */
    public function month(string $monthNumber) : Job;

    /**
     * Permonth
     * 
     * @param string $month
     * 
     * @return Job
     */
    public function perMonth(string $month) : Job;

    /**
     * Day
     * 
     * @param string $day= '*'
     * 
     * @return Job
     */
    public function day(string $day) : Job;

    /**
     * Perday
     * 
     * @param string $day
     * 
     * @return Job
     */
    public function perDay(string $day) : Job;

    /**
     * Interval
     * 
     * @param string $interval = '* * * * *'
     * 
     * @return Job
     */
    public function interval(string $interval) : Job;
}
