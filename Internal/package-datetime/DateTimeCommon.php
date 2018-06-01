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

use ZN\IS;
use ZN\Config;
use ZN\Datatype;
use ZN\Helpers\Converter;

class DateTimeCommon
{
    /**
     * Keeps datetime config.
     * 
     * @var array
     */
    protected $config;

    /**
     * Keeps Class Name
     * 
     * @var string
     */
    protected $className = 'ZN\DateTime\Date';

    /**
     * Magic Constructor
     */
    public function __construct()
    {
        $this->config = Config::default('ZN\DateTime\DateTimeDefaultConfiguration')
                              ::get('Project');

        setlocale(LC_ALL, $this->config['locale']['charset'], $this->config['locale']['language']);
    }

    /**
     * Sets locale
     * 
     * 5.7.6[added]
     * 
     * @param string $parameters
     * 
     * @return this
     */
    public function locale(...$parameters)
    {
        setlocale(LC_ALL, ...$parameters);

        return $this;
    }

    /**
     * Sets timezone
     * 
     * 5.7.6[added]
     * 
     * @param string $timezone
     * 
     * @return this
     */
    public function timezone(String $timezone)
    {
        # Sets the timezone.
        if( IS::timeZone($timezone) )
        {
            date_default_timezone_set($timezone);

            return $this;
        }

        throw new Exception\InvalidTimezoneException(NULL, $timezone);
    }

    /**
     * Compare dates
     * 
     * @param string $value1
     * @param string $condition
     * @param string $value2
     * 
     * @return bool
     */
    public function compare(String $value1, String $condition, String $value2) : Bool
    {
        $value1 = $this->toNumeric($value1);
        $value2 = $this->toNumeric($value2);
        
        return version_compare($value1, $value2, $condition);
    }

    /**
     * Turns historical data into numeric data.
     * 
     * @param string $dateFormat
     * @param int    $now = NULL
     * 
     * @return int
     */
    public function toNumeric(String $dateFormat, Int $now = NULL) : Int
    {
        if( $now === NULL )
        {
            $now = time();
        }

        return strtotime($this->_datetime($dateFormat), $now);
    }

    /**
     * Converts time data to readable form.
     * 
     * @param int $time
     * @param string $dateFormat = 'Y-m-d H:i:s'
     * 
     * @return string
     */
    public function toReadable(Int $time, String $dateFormat = 'Y-m-d H:i:s') : String
    {
        return $this->_datetime($dateFormat, $time);
    }

    /**
     * Calculates between dates.
     * 
     * @param string $input
     * @param string $calculate
     * @param string $output = 'Y-m-d'
     * 
     * @return string
     */
    public function calculate(String $input, String $calculate, String $output = 'Y-m-d') : String
    {
        if( ! preg_match('/^[0-9]/', $input) )
        {
            $input = $this->_datetime($input);
        }

        # 5.3.5[added]
        if( $this->_classname() === 'ZN\DateTime\Time' && $output === 'Y-m-d' )
        {
            $output = '{Hour}:{minute}:{second}';
        }

        $output = $this->_convert($output);

        return $this->_datetime($output, strtotime($calculate, strtotime($input)));
    }

    /**
     * Sets the date and time.
     * 
     * @param string $exp 
     * 
     * @return string
     */
    public function set(String $exp) : String
    {
        return $this->_datetime($exp);
    }

    /**
     * Protected Convert
     */
    protected function _convert($change)
    {
        $config = $this->_chartype();

        $chars  = Properties::${$config};

        $chars  = Datatype::multikey($chars);

        return str_ireplace(array_keys($chars), array_values($chars), $change);
    }

    /**
     * Protected Class Name
     */
    protected function _classname()
    {
        return $className = get_called_class();
    }

    /**
     * Protected Date Time
     */
    protected function _datetime($format, $timestamp = NULL)
    {
        if( $timestamp === NULL )
        {
            $timestamp = time();
        }

        $className = $this->_classname();

        $func = $className === $this->className ? 'date' : 'strftime';

        return $func($this->_convert($format), $timestamp);
    }

    /**
     * Protected Chartype
     */
    protected function _chartype()
    {
        $className = $this->_classname();

        return $className === $this->className ? 'setDateFormatChars' : 'setTimeFormatChars';
    }

    /**
     * Protected add day
     */
    protected function add(String $datetime, String $next = '1', $type = 'day', $signal = '+') : String
    {
        return $this->calculate($datetime, $signal . $next . $type);
    }

    /**
     * Protected remove day
     */
    protected function remove(String $datetime, String $next = '1', $type = 'day') : String
    {
        return $this->add($datetime, $next, $type, '-');
    }

    /**
     * Protected different
     */
    protected function different($date1, $date2, $output) : Float
    {
        return Converter::time($this->toNumeric($date2) - $this->toNumeric($date1), 'second', $output);
    }
}
