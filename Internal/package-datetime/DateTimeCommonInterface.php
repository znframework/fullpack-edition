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

interface DateTimeCommonInterface
{
    /**
     * Sets timezone
     * 
     * 5.7.6[added]
     * 
     * @param string $timezone
     * 
     * @return this
     */
    public function zone(string $timezone);

    /**
     * Sets locale
     * 
     * 5.7.6[added]
     * 
     * @param string $parameters
     * 
     * @return this
     */
    public function locale(...$parameters);

    /**
     * Is past
     * 
     * @string $datetime
     * 
     * @return bool
     */
    public function isPast(string $datetime) : bool;

    /**
     * Compare dates
     * 
     * @param string $value1
     * @param string $condition
     * @param string $value2
     * 
     * @return bool
     */
    public function compare(string $value1, string $condition, string $value2) : bool;

    /**
     * Turns historical data into numeric data.
     * 
     * @param string $dateFormat
     * @param int    $now = NULL
     * 
     * @return int
     */
    public function toNumeric(string $dateFormat, int $now = NULL) : int;

     /**
     * Converts time data to readable form.
     * 
     * @param int $time
     * @param string $dateFormat = 'Y-m-d H:i:s'
     * 
     * @return string
     */
    public function toReadable(int $time, string $dateFormat = 'Y-m-d H:i:s') : string;

    /**
     * Calculates between dates.
     * 
     * @param string $input
     * @param string $calculate
     * @param string $output = 'Y-m-d'
     * @param string $type   = NULL
     * 
     * @return string
     */
    public function calculate(string $input, string $calculate, string $output = 'Y-m-d', string $type = NULL) : string;

    /**
     * Sets the date and time.
     * 
     * @param string $exp 
     * 
     * @return string
     */
    public function set(string $exp) : string;

    /**
     * Gives the active time information.
     * 
     * @param string $clock
     * 
     * @return string
     */
    public function current(string $clock) : string;

    /**
     * Converts date information.
     * 
     * @param string $date
     * @param string $format
     * 
     * @return string
     */
    public function convert(string $date, string $format) : string;

    /**
     * Generates standard date and time information.
     * 
     * @return string
     */
    public function standart() : string;
}
