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
use ZN\Helpers\Rounder;
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
     * Protected split upper case
     */
    protected function splitUpperCase($method)
    {
        return  Datatype::splitUpperCase($method);
    }

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
        $expression = strtolower($parts[1]) ?? NULL;

        if( $methodType === 'diff' )
        {
            return $this->different($parameters[0], $parameters[1] ?? NULL, $expression, strtolower($parameters[2] ?? $parts[2] ?? NULL));
        }
        elseif( in_array($methodType, ['add', 'remove']) )
        {
            return $this->$methodType($parameters[0] ?? NULL, $parameters[1] ?? 1, $expression);
        }
        elseif( $methodType === 'current' )
        {
            return $this->set('{'.ltrim($method, $methodType).'}');
        }
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
     * Sets zone
     * 
     * 5.7.6[added]
     * 
     * @param string $timezone
     * 
     * @return this
     */
    public function zone(String $timezone)
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
    protected function add(String $datetime = NULL, Int $count = 1, $type = 'day', $signal = '+') : String
    {
        if( ! $this->check((string) $datetime) && is_numeric($datetime) && $count = 1 )
        {
            $count    = $datetime;
            $datetime = $this->default();
        }

        return $this->calculate($datetime ?? $this->default(), $signal . $count . $type);
    }

    /**
     * Protected remove day
     */
    protected function remove(String $datetime = NULL, Int $count = 1, $type = 'day') : String
    {
        return $this->add($datetime, $count, $type, '-');
    }

    /**
     * Protected different
     */
    protected function different($date1, $date2, $output, $round = NULL) : Float
    {
        if( $date2 === NULL )
        {
            $date2 = $date1;
            $date1 = $this->default();
        }

        $return = Converter::time($this->toNumeric($date2) - $this->toNumeric($date1), 'second', $output);

        if( ! empty($round) )
        {
            return $this->round($round, $return);
        }

        return $return;
    }

    /**
     * Protected round
     */
    protected function round($round, $return)
    {
        if( in_array($round, ['up', 'down', 'average']) )
        {
            return Rounder::$round($return);
        }
        
        return Rounder::average($return);
    }
}
