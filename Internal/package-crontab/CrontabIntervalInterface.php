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
    public function clock(String $clock) : Job;

    /**
     * Minute
     * 
     * @param string $minute
     * 
     * @return Job
     */
    public function minute(String $minute) : Job;

    /**
     * Perminute
     * 
     * @param string $minute
     * 
     * @return Job
     */
    public function perMinute(String $minute) : Job;

    /**
     * Hour
     * 
     * @param string $hour
     * 
     * @return Job
     */
    public function hour(String $hour) : Job;

    /**
     * Perhour
     * 
     * @param string $hour
     * 
     * @return Job
     */
    public function perHour(String $hour) : Job;

    /**
     * Day Number
     * 
     * @param string $dayNumber
     * 
     * @return Job
     */
    public function dayNumber(String $dayNumber) : Job;

    /**
     * Month Number
     * 
     * @param string $monthNumber
     * 
     * @return Job
     */
    public function month(String $monthNumber) : Job;

    /**
     * Permonth
     * 
     * @param string $month
     * 
     * @return Job
     */
    public function perMonth(String $month) : Job;

    /**
     * Day
     * 
     * @param string $day= '*'
     * 
     * @return Job
     */
    public function day(String $day) : Job;

    /**
     * Perday
     * 
     * @param string $day
     * 
     * @return Job
     */
    public function perDay(String $day) : Job;

    /**
     * Interval
     * 
     * @param string $interval = '* * * * *'
     * 
     * @return Job
     */
    public function interval(String $interval) : Job;
}
